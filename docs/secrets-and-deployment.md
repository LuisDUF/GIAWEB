# Despliegue seguro y operación

## Desarrollo local con Minikube

- Use el perfil y contexto `minikube`, con driver Docker. Compruebe el nodo antes de aplicar:

```powershell
minikube start --driver=docker
kubectl --context minikube get nodes -o wide
```

- Habilite `ingress` y `metrics-server` si el binario de Minikube está disponible. Si sólo está
  disponible el contexto, el despliegue sigue siendo válido para Services, Pods, PVC y políticas;
  Ingress y HPA quedan pendientes de esos addons.
- Minikube usa el StorageClass `standard`; sus volúmenes locales no son evidencia de recuperación
  entre nodos.

## Producción de laboratorio

- Use el contexto independiente `proyecto-prof` y namespace `fitness-prod`. No reutilice PVC,
  Secrets, datos ni credenciales de Minikube.
- Para observabilidad, instale `kube-prometheus-stack` con
  `infra/observability/kube-prometheus-stack-values.yaml` y configure Grafana/Alertmanager
  con secretos externos a Git.

## Crear Secrets

No guarde este comando con valores reales en el repositorio ni lo copie a una captura. Ejecute
una vez por ambiente y sustituya los marcadores en una consola local:

```powershell
kubectl --context minikube -n fitness-dev create secret generic app-secrets `
  --from-literal=APP_KEY='base64:REEMPLAZAR' `
  --from-literal=DB_DATABASE='fitness_dev' `
  --from-literal=DB_USERNAME='fitness_app' `
  --from-literal=DB_PASSWORD='REEMPLAZAR' `
  --from-literal=MYSQL_ROOT_PASSWORD='REEMPLAZAR' `
  --from-literal=DB_MONGO_USERNAME='fitness_audit' `
  --from-literal=DB_MONGO_PASSWORD='REEMPLAZAR'
```

Repita para producción, cambiando contexto, namespace y valores. Cree además los certificados
TLS como `fitness-dev-tls` y `fitness-prod-tls`. Puede usar una CA local para laboratorio;
documente su emisor y fecha de vencimiento.

## TLS local

Genere una CA y un certificado distinto por ambiente fuera del repositorio. Cada certificado debe
incluir los SAN `app.dev.local` y `api.dev.local`, o bien `app.prod.local` y `api.prod.local`.
Con los archivos temporales de certificado y llave, cree los Secrets sin imprimir sus valores:

```powershell
kubectl --context minikube -n fitness-dev create secret tls fitness-dev-tls `
  --cert=RUTA_A_CERT_DEV --key=RUTA_A_LLAVE_DEV --dry-run=client -o yaml | kubectl --context minikube apply -f -
kubectl --context proyecto-prof -n fitness-prod create secret tls fitness-prod-tls `
  --cert=RUTA_A_CERT_PROD --key=RUTA_A_LLAVE_PROD --dry-run=client -o yaml | kubectl --context proyecto-prof apply -f -
```

El patch de desarrollo usa `https://app.dev.local:8443` y
`https://api.dev.local:8443`; el puerto 8443 se usa porque el 443 local estaba ocupado. Para
probarlo, mantenga un port-forward TLS del controlador Ingress y confíe en la CA local:

```powershell
kubectl --context minikube -n ingress-nginx port-forward service/ingress-nginx-controller 8443:443
curl.exe --ssl-no-revoke --cacert RUTA_A_CA --resolve app.dev.local:8443:127.0.0.1 https://app.dev.local:8443/
```

`--ssl-no-revoke` sólo evita la comprobación de CRL inexistente de una CA local de laboratorio;
la CA y el nombre DNS siguen verificándose. El Ingress de producción se configura con los hosts
`app.prod.local` y `api.prod.local`; su prueba de aplicación requiere antes promover las imágenes
inmutables y desplegar los Services de producción.

## Construir e importar para Minikube

```powershell
docker build --build-arg PUBLIC_API_URL=https://api.dev.local:8443/api -t proyecto-kubernet/frontend:dev frontend
docker build -t proyecto-kubernet/backend:dev backend
minikube image load proyecto-kubernet/frontend:dev
minikube image load proyecto-kubernet/backend:dev
```

Si no tiene el ejecutable `minikube` pero el nodo/perfil ya está activo, use la alternativa
equivalente (Docker driver):

```powershell
docker save proyecto-kubernet/frontend:dev | docker exec -i minikube ctr -n k8s.io images import -
docker save proyecto-kubernet/backend:dev | docker exec -i minikube ctr -n k8s.io images import -
```

Para producción publique en un registro y promueva exactamente el digest probado: cambie
`newName`/`newTag` en el overlay de producción
sin volver a compilar. Antes de aplicar, borre explícitamente el Job de migración anterior si
corresponde a una nueva versión aprobada; no ejecute migraciones concurrentes desde los Pods.

## Aplicar

```powershell
kubectl --context minikube apply -k infra/overlays/dev
kubectl --context minikube -n fitness-dev wait --for=condition=complete job/backend-migrate --timeout=300s
kubectl --context minikube -n fitness-dev get pods,svc,pvc,job
kubectl --context proyecto-prof apply -k infra/overlays/prod
kubectl --context proyecto-prof -n fitness-prod get pods,svc,pvc,hpa
```

## Recuperación y respaldos

Los CronJob guardan dumps en el PVC `database-backups`, distinto a los PVC de origen. Para una
prueba válida, restaure a un namespace o instancia aislada, compare conteos, claves/índices y
pruebe la app contra la restauración. Objetivo inicial: RPO 24 h y RTO 30 min; mida y reporte
el resultado real. No afirme recuperación entre nodos si la StorageClass usa volúmenes locales.

## Rollback e incidente MongoDB

- Rollback de aplicación: `kubectl rollout undo deployment/backend` después de comprobar que
  la migración es compatible hacia atrás. El rollback de imagen no revierte esquemas.
- MongoDB temporalmente indisponible: la operación MySQL debe terminar; inspeccione
  `activity_outbox` y, al recuperar Mongo, ejecute `php artisan activity:reconcile` desde un
  Job/Pod autorizado. Verifique una sola entrada por `event_id`.
