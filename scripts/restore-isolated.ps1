[CmdletBinding()]
param(
    [Parameter(Mandatory)] [string] $Context,
    [Parameter(Mandatory)] [ValidatePattern('^[a-z0-9]([-a-z0-9]*[a-z0-9])?$')] [string] $SourceNamespace,
    [Parameter(Mandatory)] [ValidateSet('mysql', 'mongodb')] [string] $Database,
    [Parameter(Mandatory)] [ValidatePattern('^[A-Za-z0-9._-]+$')] [string] $BackupFile,
    [ValidatePattern('^[a-z0-9]([-a-z0-9]*[a-z0-9])?$')] [string] $RecoveryNodeName,
    [ValidatePattern('^[a-z0-9]([-a-z0-9]*[a-z0-9])?$')] [string] $TargetNamespace = "fitness-restore-$(Get-Date -Format 'yyyyMMddHHmmss')"
)

$ErrorActionPreference = 'Stop'
$remoteObject = "backup-crypt:fitness/$SourceNamespace/$Database/$BackupFile"
$checksumObject = "$remoteObject.sha256"
$workloadNodePlacement = if ($RecoveryNodeName) { "      nodeName: $RecoveryNodeName" } else { '' }
$jobNodePlacement = if ($RecoveryNodeName) { "      nodeName: $RecoveryNodeName" } else { '' }

function Apply-Yaml([string] $yaml) {
    $yaml | kubectl --context $Context -n $TargetNamespace apply -f -
    if ($LASTEXITCODE -ne 0) { throw 'kubectl apply failed.' }
}

# The target namespace is deliberately separate. Do not point this script at the
# application namespace or at the production StatefulSets. Applying Restricted
# here makes the recovery path subject to the same Pod Security baseline.
Apply-Yaml @"
apiVersion: v1
kind: Namespace
metadata:
  name: $TargetNamespace
  labels:
    pod-security.kubernetes.io/enforce: restricted
    pod-security.kubernetes.io/audit: restricted
    pod-security.kubernetes.io/warn: restricted
"@

# Secrets are deliberately not copied by this script. Before running it, create
# backup-object-storage (read-only object-store credentials + rclone.conf) and
# restore-target in the target namespace as documented in secrets-and-deployment.md.
foreach ($secret in 'backup-object-storage', 'restore-target') {
    kubectl --context $Context -n $TargetNamespace get secret $secret | Out-Null
    if ($LASTEXITCODE -ne 0) { throw "Missing Secret '$secret' in namespace '$TargetNamespace'." }
}

# A recovery namespace starts default-deny. Only the restore database, its Job,
# cluster DNS and the encrypted object-store endpoint can communicate. The
# policy is created before the workloads so a restore cannot transiently run
# with unrestricted pod traffic.
Apply-Yaml @"
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata: { name: default-deny }
spec:
  podSelector: {}
  policyTypes: [Ingress, Egress]
---
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata: { name: allow-dns }
spec:
  podSelector: {}
  policyTypes: [Egress]
  egress:
    - to:
        - namespaceSelector: { matchLabels: { kubernetes.io/metadata.name: kube-system } }
          podSelector: { matchLabels: { k8s-app: kube-dns } }
      ports:
        - { protocol: UDP, port: 53 }
        - { protocol: TCP, port: 53 }
---
apiVersion: networking.k8s.io/v1
kind: NetworkPolicy
metadata: { name: allow-restore-components }
spec:
  podSelector: { matchLabels: { app.kubernetes.io/name: backup-restore } }
  policyTypes: [Ingress, Egress]
  ingress:
    - from:
        - podSelector: { matchLabels: { app.kubernetes.io/name: backup-restore } }
      ports:
        - { protocol: TCP, port: 3306 }
        - { protocol: TCP, port: 27017 }
  egress:
    - to:
        - podSelector: { matchLabels: { app.kubernetes.io/name: backup-restore } }
      ports:
        - { protocol: TCP, port: 3306 }
        - { protocol: TCP, port: 27017 }
    - to: [{ ipBlock: { cidr: 0.0.0.0/0 } }]
      ports:
        - { protocol: TCP, port: 443 }
        - { protocol: TCP, port: 9000 }
"@

if ($Database -eq 'mysql') {
    Apply-Yaml @"
apiVersion: v1
kind: Service
metadata: { name: mysql-restore }
spec:
  selector: { app.kubernetes.io/name: backup-restore, app.kubernetes.io/component: mysql }
  ports: [{ name: mysql, port: 3306 }]
---
apiVersion: apps/v1
kind: Deployment
metadata: { name: mysql-restore }
spec:
  replicas: 1
  selector: { matchLabels: { app.kubernetes.io/name: backup-restore, app.kubernetes.io/component: mysql } }
  template:
    metadata: { labels: { app.kubernetes.io/name: backup-restore, app.kubernetes.io/component: mysql } }
    spec:
$workloadNodePlacement
      automountServiceAccountToken: false
      securityContext: { fsGroup: 999, seccompProfile: { type: RuntimeDefault } }
      containers:
        - name: mysql
          image: mysql:8.4.4
          envFrom: [{ secretRef: { name: restore-target } }]
          ports: [{ name: mysql, containerPort: 3306 }]
          readinessProbe: { tcpSocket: { port: mysql }, initialDelaySeconds: 5, periodSeconds: 5, failureThreshold: 24 }
          resources: { requests: { cpu: 250m, memory: 512Mi }, limits: { cpu: '1', memory: 1Gi } }
          securityContext: { allowPrivilegeEscalation: false, readOnlyRootFilesystem: false, runAsNonRoot: true, runAsUser: 999, capabilities: { drop: ['ALL'] } }
          volumeMounts: [{ name: data, mountPath: /var/lib/mysql }]
      volumes: [{ name: data, emptyDir: {} }]
"@
    kubectl --context $Context -n $TargetNamespace rollout status deployment/mysql-restore --timeout=300s
    $restoreCommand = 'sha256sum -c /work/{0}.sha256 && mysql --host=mysql-restore --user=root --password="$MYSQL_ROOT_PASSWORD" --execute="CREATE DATABASE IF NOT EXISTS \`$RESTORE_MYSQL_DATABASE\`;" && gzip -dc /work/{0} | mysql --host=mysql-restore --user=root --password="$MYSQL_ROOT_PASSWORD" "$RESTORE_MYSQL_DATABASE"' -f $BackupFile
    $restoreImage = 'mysql:8.4.4'
} else {
    Apply-Yaml @"
apiVersion: v1
kind: Service
metadata: { name: mongodb-restore }
spec:
  selector: { app.kubernetes.io/name: backup-restore, app.kubernetes.io/component: mongodb }
  ports: [{ name: mongodb, port: 27017 }]
---
apiVersion: apps/v1
kind: Deployment
metadata: { name: mongodb-restore }
spec:
  replicas: 1
  selector: { matchLabels: { app.kubernetes.io/name: backup-restore, app.kubernetes.io/component: mongodb } }
  template:
    metadata: { labels: { app.kubernetes.io/name: backup-restore, app.kubernetes.io/component: mongodb } }
    spec:
$workloadNodePlacement
      automountServiceAccountToken: false
      securityContext: { fsGroup: 999, seccompProfile: { type: RuntimeDefault } }
      containers:
        - name: mongodb
          image: mongo:8.0.4
          envFrom: [{ secretRef: { name: restore-target } }]
          ports: [{ name: mongodb, containerPort: 27017 }]
          readinessProbe: { tcpSocket: { port: mongodb }, initialDelaySeconds: 5, periodSeconds: 5, failureThreshold: 24 }
          resources: { requests: { cpu: 200m, memory: 384Mi }, limits: { cpu: '1', memory: 1Gi } }
          securityContext: { allowPrivilegeEscalation: false, readOnlyRootFilesystem: false, runAsNonRoot: true, runAsUser: 999, capabilities: { drop: ['ALL'] } }
          volumeMounts: [{ name: data, mountPath: /data/db }]
      volumes: [{ name: data, emptyDir: {} }]
"@
    kubectl --context $Context -n $TargetNamespace rollout status deployment/mongodb-restore --timeout=300s
    $restoreCommand = 'sha256sum -c /work/{0}.sha256 && mongorestore --host mongodb-restore --username "$MONGO_INITDB_ROOT_USERNAME" --password "$MONGO_INITDB_ROOT_PASSWORD" --authenticationDatabase admin --nsInclude="$SOURCE_MONGO_DATABASE.*" --nsFrom="$SOURCE_MONGO_DATABASE.*" --nsTo="$RESTORE_MONGO_DATABASE.*" --archive=/work/{0} --gzip --drop' -f $BackupFile
    $restoreImage = 'mongo:8.0.4'
}

$jobName = "$Database-restore-$(Get-Date -Format 'yyyyMMddHHmmss')"
Apply-Yaml @"
apiVersion: batch/v1
kind: Job
metadata: { name: $jobName }
spec:
  backoffLimit: 0
  template:
    metadata: { labels: { app.kubernetes.io/name: backup-restore } }
    spec:
$jobNodePlacement
      restartPolicy: Never
      automountServiceAccountToken: false
      securityContext: { fsGroup: 999, seccompProfile: { type: RuntimeDefault } }
      initContainers:
        - name: download-and-decrypt
          image: rclone/rclone:1.69.1
          command: ["/bin/sh", "-ec", "rclone --config /config/rclone.conf copyto '$remoteObject' /work/$BackupFile --checksum && rclone --config /config/rclone.conf copyto '$checksumObject' /work/$BackupFile.sha256 --checksum"]
          resources: { requests: { cpu: 50m, memory: 64Mi }, limits: { cpu: 250m, memory: 256Mi } }
          securityContext: { allowPrivilegeEscalation: false, readOnlyRootFilesystem: true, runAsNonRoot: true, runAsUser: 65532, capabilities: { drop: ['ALL'] } }
          volumeMounts:
            - { name: work, mountPath: /work }
            - { name: rclone-config, mountPath: /config, readOnly: true }
      containers:
        - name: restore
          image: $restoreImage
          command: ["/bin/sh", "-ec", '$restoreCommand']
          envFrom: [{ secretRef: { name: restore-target } }]
          resources: { requests: { cpu: 100m, memory: 128Mi }, limits: { cpu: 500m, memory: 512Mi } }
          securityContext: { allowPrivilegeEscalation: false, readOnlyRootFilesystem: true, runAsNonRoot: true, runAsUser: 999, capabilities: { drop: ['ALL'] } }
          volumeMounts: [{ name: work, mountPath: /work, readOnly: true }]
      volumes:
        - { name: work, emptyDir: {} }
        - name: rclone-config
          secret: { secretName: backup-object-storage }
"@

kubectl --context $Context -n $TargetNamespace wait --for=condition=complete "job/$jobName" --timeout=600s
if ($LASTEXITCODE -ne 0) { throw "Restore Job failed; inspect: kubectl --context $Context -n $TargetNamespace logs job/$jobName" }

Write-Host "Restore completed in isolated namespace '$TargetNamespace'. Validate counts and indexes before cleanup."
Write-Host "Cleanup only after recording results: kubectl --context $Context delete namespace $TargetNamespace"
