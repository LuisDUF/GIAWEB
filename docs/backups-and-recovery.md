# Respaldos y restauración reproducibles

Este documento define el procedimiento versionado para respaldar MySQL y MongoDB
sin usar los PVC de datos como destino de la copia. Cumple el requisito de la
práctica de usar un destino separado, restaurar en un entorno aislado y registrar
resultados reales en lugar de declararlos por anticipado.

## Cambios versionados de reproducibilidad

- Se sustituyó el PVC local `database-backups` por un flujo temporal de dump,
  checksum y carga cifrada a almacenamiento de objetos.
- Se versionaron los ConfigMaps de política y script de carga, los CronJobs de
  ambos motores, la política de salida explícita para respaldos y el ingreso de
  Prometheus exclusivamente a los exporters.
- El script de restauración ahora crea o refuerza Pod Security `restricted` y
  NetworkPolicies de denegación inicial dentro del namespace aislado.
- La validación de infraestructura falla si un overlay deja de incluir estos
  controles o vuelve a declarar el PVC local como destino de respaldo.

## Diseño

- `mysql-backup` se programa a las `02:15` y usa `mysqldump --single-transaction`.
- `mongodb-backup` se programa a las `02:30` y usa `mongodump --archive --gzip`.
- Cada dump se comprime, se acompaña de un archivo SHA-256 y se carga con
  `rclone` a `backup-crypt:fitness/<namespace>/<motor>`.
- `backup-crypt` debe ser un remote de tipo `crypt`; así, tanto los nombres como
  el contenido llegan cifrados al almacenamiento compatible con objetos.
- La retención declarada es de 14 días. Los Jobs que terminen con error se
  conservan para diagnóstico.

El archivo `rclone.conf` y todas las contraseñas siguen siendo Secrets creados
fuera de Git. El destino remoto no se monta como PVC y no comparte el volumen
de MySQL o MongoDB.

Los resultados reales de las ejecuciones se registran en
[backup-execution-log.md](backup-execution-log.md); ese registro debe actualizarse
después de cada prueba o incidente.

## Preparar los Secrets

Haga esto una vez por ambiente, utilizando un `rclone.conf` local que defina el
remote `backup-crypt`. No imprima el archivo ni lo agregue al repositorio.

```powershell
kubectl --context proyecto-prof -n fitness-prod create secret generic backup-object-storage `
  --from-file=rclone.conf=RUTA_SEGURA_A_RCLONE_CONF `
  --dry-run=client -o yaml | kubectl --context proyecto-prof apply -f -
```

Después aplique los manifiestos del ambiente. Kustomize genera los ConfigMaps
`backup-policy` y `backup-scripts`; el Secret anterior es la única entrada no
versionada necesaria para los CronJobs.

```powershell
kubectl --context proyecto-prof apply -k infra/overlays/prod
.\tests\infrastructure\validate.ps1 -Environment prod
```

## Comprobar un respaldo

Ejecute un Job manual con un nombre único y guarde la salida, fecha, tamaño del
objeto y checksum en la bitácora de evidencias. No marque el respaldo como
correcto sólo porque el CronJob exista.

```powershell
$job = "mysql-backup-manual-$(Get-Date -Format yyyyMMddHHmmss)"
kubectl --context proyecto-prof -n fitness-prod create job --from=cronjob/mysql-backup $job
kubectl --context proyecto-prof -n fitness-prod wait --for=condition=complete job/$job --timeout=15m
kubectl --context proyecto-prof -n fitness-prod logs job/$job -c dump
kubectl --context proyecto-prof -n fitness-prod logs job/$job -c encrypt-upload-prune
```

Repita el procedimiento con `mongodb-backup`. Si un Job alcanza `BackoffLimitExceeded`,
registre el incidente y no declare cumplido el RPO hasta que exista una ejecución exitosa.

## Restauración aislada

No restaure nunca sobre `fitness-prod`. El script crea o refuerza un namespace
con Pod Security `restricted` y NetworkPolicies de denegación inicial, DNS, acceso
al motor temporal y acceso HTTPS/9000 al almacenamiento de objetos.

Elija explícitamente el namespace de destino para poder crear sus Secrets antes
de ejecutar el script:

```powershell
$restoreNamespace = 'fitness-restore-mysql-20261101'
kubectl --context proyecto-prof create namespace $restoreNamespace --dry-run=client -o yaml | kubectl apply -f -
# Contiene rclone.conf de sólo lectura para backup-crypt.
kubectl --context proyecto-prof -n $restoreNamespace create secret generic backup-object-storage `
  --from-file=rclone.conf=RUTA_SEGURA_A_RCLONE_CONF

# Para MySQL: MYSQL_ROOT_PASSWORD y RESTORE_MYSQL_DATABASE.
kubectl --context proyecto-prof -n $restoreNamespace create secret generic restore-target `
  --from-literal=MYSQL_ROOT_PASSWORD='REEMPLAZAR' `
  --from-literal=RESTORE_MYSQL_DATABASE='fitness_restore'

.\scripts\restore-isolated.ps1 `
  -Context proyecto-prof `
  -SourceNamespace fitness-prod `
  -Database mysql `
  -BackupFile NOMBRE_EXACTO_DEL_ARCHIVO `
  -TargetNamespace $restoreNamespace
```

Para MongoDB, `restore-target` requiere `MONGO_INITDB_ROOT_USERNAME`,
`MONGO_INITDB_ROOT_PASSWORD`, `SOURCE_MONGO_DATABASE` y `RESTORE_MONGO_DATABASE`.
Ejecute el mismo script con `-Database mongodb`.

Después de cada restauración, compruebe al menos: checksum, conteos de tablas o
colecciones, relaciones seleccionadas, el índice único `event_id` de
`activity_logs` y una consulta de la aplicación dirigida al entorno recuperado.
Registre hora de inicio, fin, duración y resultado; ése es el RTO medido, no una
estimación. El objetivo inicial es RPO de 24 horas y RTO de 30 minutos.

Elimine el namespace temporal sólo después de registrar la evidencia:

```powershell
kubectl --context proyecto-prof delete namespace $restoreNamespace
```

## Límites conocidos

Un clúster Minikube multinodo sobre un único host sigue teniendo un dominio de
fallo compartido. El almacenamiento remoto cifrado protege el respaldo frente a
la pérdida del PVC local, pero no reemplaza una prueba periódica de restauración.
