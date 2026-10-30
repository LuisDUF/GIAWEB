# Bitácora de implementación y validación

Fecha: 28 de octubre de 2026. Esta bitácora registra sólo comandos ejecutados por la
implementación y no contiene credenciales.

## Paso 1 - Inspección y precondiciones

**Comando:** `Get-Content -Raw frontend\\AGENTS.md` y comprobación de `docker`, `kubectl`,
`kustomize`, `helm`, `kind`, `minikube` y `kubeconform`.

**Resultado:** Docker y kubectl están instalados. Kustomize se usa mediante `kubectl kustomize`.
Helm, Kind, Minikube y kubeconform no están instalados, por lo que no se creó ni se modificó un
clúster ni se ejecutó validación por esquema externo. El repositorio tenía un PDF eliminado por
el usuario; se preservó esa eliminación.

## Paso 2 - Diagnóstico inicial

**Comando:** búsqueda `rg` de Docker, Kubernetes, Helm, Kustomize, salud, backups y
observabilidad.

**Resultado:** no existían Dockerfiles, manifiestos, overlays ni configuración de observabilidad.
Se detectaron URLs fijas `127.0.0.1` en dos componentes de analíticas, configuración Laravel de
cache/sesión/cola local y observers que sólo registraban la caída de MongoDB.

## Paso 3 - Cambios efectuados

1. Se agregaron Dockerfiles sin secretos para Astro/Nginx y Laravel/PHP-FPM.
2. Se reemplazaron URLs rígidas en `Racha.astro` y `Sesiones.astro` por `PUBLIC_API_URL`.
3. Se añadieron `/api/health` (liveness) y `/api/ready` (readiness de MySQL y MongoDB).
4. Se creó outbox relacional, job de cola y comando `activity:reconcile`; MongoDB recibe
   documentos idempotentes por `event_id`.
5. Se retiraron rutas web generadas que apuntaban a controladores inexistentes.
6. Se añadieron Kustomize base y overlays dev/prod, PSS Restricted, NetworkPolicy, Ingress TLS,
   PVC, StatefulSets, Redis, HPA/PDB, respaldos y configuración de observabilidad.

## Paso 4 - Validaciones ejecutadas

**Comando:** `php -l` sobre las rutas, observers, modelos, migración, job, servicio y comando nuevos.

**Resultado:** todos los archivos analizados devolvieron `No syntax errors detected`.

**Comando inicial:** `npm run build` desde la raíz del repositorio.

**Resultado:** falló correctamente con `ENOENT` porque no existe `package.json` en la raíz.
La corrección fue ejecutar el mismo comando desde `frontend`.

**Comando corregido:** `npm run build` en `frontend`.

**Resultado:** correcto; Astro generó 6 rutas estáticas en `frontend/dist`. Emitió una advertencia
de chunks mayores de 500 kB, que debe optimizarse antes de una medición de rendimiento, pero no
impide el build.

**Comando:** `kubectl kustomize infra/overlays/dev`.

**Resultado:** renderizó correctamente; kubectl emitió aviso de deprecación sobre
`patchesStrategicMerge`, no un error de manifiesto.

**Comando inicial:** `kubectl kustomize infra/overlays/prod`.

**Resultado:** identificó una duplicación de ConfigMap por tratar el archivo de patch como
resource. Se corrigió separando los recursos nuevos HPA/PDB en `scale.yaml` y usando
`patchesStrategicMerge` para el patch.

**Comando posterior:** `kubectl kustomize infra/overlays/dev` y
`kubectl kustomize infra/overlays/prod`.

**Resultado:** ambos overlays renderizaron con código de salida 0. Sólo queda una advertencia de
deprecación de Kustomize que no afecta el manifiesto actual.

**Comandos:** `php artisan route:list --path=api` y `php artisan test`, desde `backend`.

**Resultado:** la ruta ahora descubre 39 endpoints, incluidos `/api/health` y `/api/ready`.
Las 2 pruebas existentes pasaron; son pruebas genéricas y no sustituyen las pruebas funcionales
de CRUD, roles y recuperación que deben añadirse como siguiente fase.

**Comando:** `kubectl apply --dry-run=client --validate=false` para cada overlay renderizado.

**Resultado:** no se pudo completar porque kubectl necesita la API de un clúster para reconocer
los recursos y no hay servidor en `localhost:8080`. Esto no invalida el renderizado Kustomize.

**Comando:** `docker info --format '{{.ServerVersion}}'`.

**Resultado:** Docker CLI está instalado, pero Docker Desktop/Linux Engine no está iniciado.
Por ello no se construyeron ni ejecutaron imágenes; inicie Docker Desktop y ejecute los comandos
del manual para completar esta evidencia.

## Paso 5 - Comprobación final

**Comandos:** `php -l`, `php artisan list --raw`, `php artisan route:list --path=api`,
`kubectl kustomize infra/overlays/dev`, `kubectl kustomize infra/overlays/prod` y
`git diff --check`.

**Resultado:** sintaxis PHP correcta; Laravel descubre `activity:mongo-indexes` y
`activity:reconcile`; las dos rutas de salud están registradas; dev renderiza 864 líneas y prod
918 líneas de manifiestos; no hay errores de espacios en el diff. Se restauró el archivo de
caché de PHPUnit que fue alterado únicamente por la ejecución de pruebas.

## Incidencias de consola corregidas

- Un primer comando PowerShell de inventario usó una interpolación inválida de variable antes de
  ejecutar acciones; se corrigió y se volvió a lanzar.
- La comprobación exploratoria `php -` no está admitida por el binario PHP de XAMPP (`Could not
  open input file: -`). Se sustituyó por búsqueda directa de la dependencia, que confirmó que la
  conexión MongoDB ofrece `getMongoDB()`.

## Paso 6 - Prueba añadida

**Comandos:** `php artisan test`, `.\\tests\\infrastructure\\validate.ps1 -Environment dev` y
`.\\tests\\infrastructure\\validate.ps1 -Environment prod`.

**Resultado:** 3 pruebas y 4 aserciones pasaron, incluida la prueba nueva de liveness que verifica
que `/api/health` no depende de las bases. Los scripts de infraestructura renderizaron dev y prod
correctamente. PHPUnit advierte que `phpunit.xml` usa un esquema XML deprecado; no se modificó
porque es una actualización independiente de la infraestructura.

## Evidencia pendiente por ambiente real

Faltan clústeres, DNS/hosts locales, controlador Ingress, StorageClass, Metrics Server, Secrets,
registro de imágenes y certificado TLS para aplicar los overlays. Una vez disponibles, registre
la salida de los comandos de [secrets-and-deployment.md](secrets-and-deployment.md) junto con
capturas E01-E14, tiempos de backup/restauración, HPA y pruebas de red.

## Paso 7 - Docker Desktop y despliegue local (28-10-2026)

**Comandos:** `docker info --format '{{.ServerVersion}}'`, `docker build -t
proyecto-kubernet/frontend:dev frontend`, `docker build -t proyecto-kubernet/backend:dev
backend`, `docker run -d --rm --name fitness-frontend-check -p 18080:8080
proyecto-kubernet/frontend:dev` e `Invoke-WebRequest http://localhost:18080/health`.

**Resultado:** Docker Desktop quedó operativo (Engine 29.8.1). Las dos imágenes se construyeron
en una primera validación; el frontend respondió HTTP 200 en `/health` y la imagen backend
expuso `mongodb` y `redis`. Las imágenes se ejecutan sin root (frontend UID 101, backend
`www-data`). Se detuvo el contenedor temporal con `docker stop fitness-frontend-check`.

**Comandos:** `kubectl config get-contexts`, `kubectl get nodes -o wide`, `kubectl create
namespace fitness-dev`, creación local de `app-secrets` (contraseñas y APP_KEY aleatorias, sin
imprimir valores) y `kubectl apply -k infra/overlays/dev`.

**Resultado:** el contexto `docker-desktop` y el nodo `desktop-control-plane` estuvieron
`Ready`. Se aplicaron namespace, servicios, bases, workloads, políticas de red, Job de
migración y CronJobs. MySQL, MongoDB, Redis y frontend alcanzaron estado `Running`.

**Correcciones encontradas durante el despliegue:** se agregó `/tmp` como `emptyDir` para Nginx
con sistema de archivos de sólo lectura; se preparan directorios de `storage` de Laravel en
init containers; la NetworkPolicy autoriza explícitamente backend, worker, migración y respaldos
hacia las bases; y `UserSeeder` ajusta el autoincremento según MySQL o PostgreSQL, en vez de
usar incondicionalmente `setval` de PostgreSQL.

**Incidencia abierta:** MongoDB 8 requiere SSL para SCRAM-SHA-256. Se añadió `openssl-dev` y
la opción `with-mongodb-ssl=openssl` a la compilación PECL. La descarga de `mongodb-2.5.5.tgz`
desde PECL se truncó en tres intentos (`unable to unpack`), por lo que la imagen corregida aún no
pudo publicarse al clúster. El despliegue queda parcialmente operativo, pero backend readiness y
el Job de migración no deben considerarse aprobados hasta que esa descarga finalice y se repitan
`kubectl rollout restart`, el Job y `/api/ready`.

## Paso 10 - Restauración de Minikube y reconstrucción TLS

**Comandos:** se instaló Minikube v1.39.0 en `.tools/minikube.exe` sin privilegios de
administrador; `minikube start --profile minikube --driver=docker` restauró el perfil existente y
su nodo quedó `Ready`. Se ejecutaron los builds de frontend y backend y
`minikube image load proyecto-kubernet/frontend:dev` / `backend:dev`.

**Resultado de TLS:** `docker run --rm proyecto-kubernet/backend:dev php -i` confirmó
`MongoDB support => enabled`, `libmongoc SSL => enabled` y `libmongoc SSL library => OpenSSL`.
Por tanto, la imagen ya soporta SCRAM-SHA-256 de MongoDB 8.

**Despliegue y validación:** se reiniciaron `backend` y `backend-worker`, se recreó
`backend-migrate` y se aplicó el overlay dev. La validación no aprobó `/api/ready`: PHP-FPM del
Pod backend termina inmediatamente con código 139 (segmentation fault), produciendo HTTP 502 en
Nginx; el Job de migración también falla. PHP CLI y `php-fpm -F` sí arrancan desde la misma imagen
fuera de Kubernetes. El siguiente diagnóstico debe comparar el securityContext y los mounts del
Pod, sin declarar disponible el backend hasta resolver este crash.

## Paso 11 - Aislamiento de seguridad de PHP-FPM

**Pruebas:** PHP-FPM se ejecutó fuera de Kubernetes con UID 82, raíz de sólo lectura, todas las
capabilities eliminadas, `no-new-privileges` y `/tmp` temporal; permaneció activo. Se agregó el
volumen temporal `/tmp` a PHP-FPM, worker y migración y se volvió a aplicar el overlay.

**Resultado:** el fallo persiste dentro de Minikube. `dmesg` del nodo registra señal 11 para
`php` y `php-fpm` (UID 82), mientras que Docker directo funciona. Esto descarta que el
securityContext básico, `readOnlyRootFilesystem` o la ausencia inicial de `/tmp` sean la causa
única. No se debilitó la política de seguridad. El siguiente cambio técnico es migrar la imagen
PHP de Alpine/musl a una base Debian/glibc y volver a compilar la extensión MongoDB con OpenSSL,
para evitar la incompatibilidad nativa observada en el runtime Minikube/WSL.

## Paso 8 - Migración del laboratorio a Minikube

**Decisión:** el ambiente de desarrollo se ejecuta en el contexto `minikube` con driver Docker.
El overlay productivo continúa destinado a un contexto independiente `proyecto-prod`; Minikube no
se presenta como producción ni comparte sus datos persistentes con ella.

**Comandos:** `kubectl --context minikube get nodes -o wide`, `docker save
proyecto-kubernet/frontend:dev | docker exec -i minikube ctr -n k8s.io images import -`, el mismo
comando para backend, creación de `fitness-dev` y de `app-secrets`, y `kubectl --context minikube
apply -k infra/overlays/dev`.

**Resultado:** el nodo `minikube` respondió `Ready` (Kubernetes v1.37.0); las dos imágenes se
importaron al runtime containerd del nodo y el overlay creó correctamente sus recursos en
`fitness-dev`. No se registraron valores de secretos en consola ni en esta bitácora.

**Incidencias:** el ejecutable `minikube` no estaba en PATH. Chocolatey no pudo instalarlo sin
consola elevada, por lo que se documentó la carga equivalente mediante `docker save`/`ctr`. Tras
aplicar, una consulta posterior a la API devolvió `TLS handshake timeout`; esto debe revisarse
cuando Minikube recupere respuesta antes de aceptar el estado de los Pods. La corrección pendiente
de la imagen backend (SSL de MongoDB y descarga PECL) sigue siendo independiente de Minikube.

## Paso 9 - Recuperación de runtime y compilación TLS

**Diagnóstico:** tras el primer despliegue, tanto `kubectl --context minikube` como
`kubectl --context docker-desktop` devolvieron `TLS handshake timeout`. Docker dejó de responder y
posteriormente reportó `read-only file system` al iniciar el contenedor Minikube y `input/output
error` en el snapshotter overlayfs. Se intentó un reinicio de Docker Desktop y un `wsl --shutdown`;
después de ello Docker Desktop informó que no puede iniciar.

**Acción no realizada por seguridad:** no se ejecutó factory reset ni se eliminaron imágenes,
volúmenes o el perfil Minikube. Para continuar el laboratorio, inicie Docker Desktop y, si sigue
sin arrancar, use su opción de reparación conservando datos o solicite soporte; sólo autorice un
reset si acepta perder los recursos locales.

**Corrección preparada:** el Dockerfile descarga `mongodb-2.5.5` y `redis-6.3.0` con `curl`
(`--retry 5 --retry-all-errors`) y compila MongoDB con
`with-mongodb-ssl=openssl`. Esto evita las descargas PECL truncadas observadas y habilita
SCRAM-SHA-256 de MongoDB 8. La verificación pendiente, una vez recuperado Docker, es construir,
importar ambas imágenes en Minikube, reiniciar backend/worker y recrear el Job de migración.

## Paso 12 - Acceso HTTP local mediante Ingress de Minikube

**Diagnóstico del 404:** `curl -i http://app.dev.local` respondió `Server:
Microsoft-HTTPAPI/2.0` y HTTP 404. Por ello, la respuesta la genera el servicio HTTP de Windows
que ocupa el puerto 80, antes de llegar a Minikube; no es un 404 de Astro, Laravel ni Nginx
Ingress. En contraste, al ejecutar `kubectl --context minikube -n ingress-nginx port-forward
service/ingress-nginx-controller 8080:80` y enviar el encabezado `Host` correspondiente, Ingress
identificó correctamente ambos hosts.

**Cambio exclusivo de desarrollo:** el patch `infra/overlays/dev/patch.yaml` establece
`nginx.ingress.kubernetes.io/ssl-redirect: "false"`, `APP_URL` y CORS con
`http://...:8080`. El overlay de producción no fue modificado y conserva HTTPS. Se reconstruyó el
frontend con:

```powershell
docker build --build-arg PUBLIC_API_URL=http://api.dev.local:8080/api -t proyecto-kubernet/frontend:dev .\frontend
.\.tools\minikube.exe image load proyecto-kubernet/frontend:dev
kubectl --context minikube apply -k .\infra\overlays\dev
kubectl --context minikube -n fitness-dev rollout restart deployment/frontend
kubectl --context minikube -n fitness-dev rollout restart deployment/backend deployment/backend-worker
```

**Resultado:** el build de Astro terminó correctamente (6 rutas estáticas), los despliegues
`frontend`, `backend` y `backend-worker` completaron su rollout y las comprobaciones a través del
Ingress devolvieron HTTP 200: `/` con `Content-Type: text/html` y `/api/ready` con
`Content-Type: application/json`. Con `Origin: http://app.dev.local:8080`, la API respondió
`Access-Control-Allow-Origin: http://app.dev.local:8080`.

**Uso en Windows:** mientras el port-forward anterior siga activo, el archivo hosts debe contener
`127.0.0.1 app.dev.local` y `127.0.0.1 api.dev.local` (reemplazando la entrada
`192.168.49.2`). Se prueba en `http://app.dev.local:8080` y
`http://api.dev.local:8080/api/ready`. El puerto 80 no se usa ni se libera para evitar afectar
servicios del sistema.

## Paso 13 - Corrección y validación de seeders MySQL

**Error recibido:** al ejecutar `kubectl --context minikube -n fitness-dev exec
deployment/backend -c php-fpm -- php artisan migrate:fresh --seed`, el proceso falló en
`WeekDaySeeder` con `FUNCTION fitness_dev.setval does not exist`. `setval` es una función de
PostgreSQL, mientras que el ambiente de desarrollo usa MySQL.

**Corrección:** se creó `database/seeders/Concerns/ResetsPrimaryKeySequence.php`. En PostgreSQL
ejecuta `setval`; en MySQL ejecuta `ALTER TABLE ... AUTO_INCREMENT`. Se aplicó a todos los
seeders que importan IDs explícitos. Además, `NormalizesCsvTimestamps.php` convierte las fechas
del CSV con milisegundos y zona horaria a `Y-m-d H:i:s` UTC, formato admitido por MySQL. Esto
resolvió el segundo error detectado en `RoutineSeeder`:
`Incorrect datetime value: '2026-10-27 15:18:26.893 -0600'`.

**Comandos de despliegue y resultado:** se ejecutaron `php -l` para todos los seeders, la
reconstrucción con `docker build -t proyecto-kubernet/backend:dev .\backend`, la importación
`minikube image load`, y el reinicio de `backend` y `backend-worker`. Después,
`kubectl ... exec deployment/backend -c php-fpm -- php artisan db:seed` completó los 12 seeders
sin errores. `php artisan migrate:status` mostró las 16 migraciones como `Ran`.

**Validación final:** se reinició el port-forward del controlador Ingress en el puerto 8080.
`curl -H "Host: api.dev.local" http://127.0.0.1:8080/api/ready` respondió HTTP 200 y
`{"status":"ready","checks":{"relational":"ok","mongodb":"ok"}}`. Los Pods
backend, worker, frontend, MySQL, MongoDB y Redis quedaron `Running`.

**Job de liberación:** el Job `backend-migrate` aún mostraba el fallo histórico. Se eliminó y se
recreó mediante `kubectl --context minikube -n fitness-dev delete job backend-migrate` y
`kubectl --context minikube apply -k .\infra\overlays\dev`, tal como indica su manifiesto. El
resultado fue `Complete 1/1` en 5 segundos: no había migraciones pendientes, los 12 seeders
terminaron correctamente y se confirmaron los índices de actividad en MongoDB.

## Paso 14 - Clúster de producción de laboratorio y HTTPS local

**Topología:** se instaló Minikube v1.39.0 con el perfil y contexto independiente
`proyecto-prof`, Kubernetes v1.37.0, containerd y CNI Calico. Tiene un control plane
(`proyecto-prof`) y dos workers (`proyecto-prof-m02`, `proyecto-prof-m03`), todos `Ready`.
Cada nodo se creó con 2 CPU y 1.8 GiB de memoria porque Docker Desktop dispone de 8 GiB. Es una
capacidad inicial de laboratorio; debe aumentarse antes de incorporar observabilidad y toda la
carga de producción.

**TLS:** se generó una CA local fuera del repositorio y un certificado distinto para dev y prod,
con SAN para `app.dev.local`/`api.dev.local` y `app.prod.local`/`api.prod.local`. Se crearon los
Secrets `fitness-dev-tls` en `fitness-dev` y `fitness-prod-tls` en `fitness-prod`, ambos de tipo
`kubernetes.io/tls`. Las llaves de servidor temporales se eliminaron después de cargar los
Secrets; la CA se mantiene fuera de Git para validación y futuras rotaciones.

**Cambios de desarrollo:** `infra/overlays/dev/patch.yaml` usa HTTPS en `APP_URL` y CORS, y
restaura `nginx.ingress.kubernetes.io/ssl-redirect: "true"`. El frontend se reconstruyó con
`PUBLIC_API_URL=https://api.dev.local:8443/api`, se cargó al perfil Minikube y se reiniciaron los
Deployments frontend, backend y worker.

**Validación:** mediante port-forward TLS `8443:443`, `curl.exe` con la CA local y resolución
forzada devolvió HTTP 200 para `https://app.dev.local:8443/` y
`https://api.dev.local:8443/api/ready`, con `ssl_verify_result=0`. La URL HTTP de desarrollo
devolvió 308 hacia HTTPS. Se habilitó el addon Ingress en `proyecto-prof`, se creó su Ingress con
los hosts productivos y el certificado validó con `ssl_verify_result=0`; el HTTP 503 observado es
esperado porque todavía no se han promovido las imágenes ni creado los Services/pods de
producción.

## Paso 15 - Conservación de HTTPS en `/dashboard`

**Incidencia y causa:** al autenticar en `https://app.dev.local:8443`, el navegador solicitaba
`/dashboard` (sin barra final). El Nginx del contenedor frontend, que escucha internamente en
HTTP puerto 8080, detectaba el directorio y respondía un `301` absoluto con
`http://app.dev.local:8080/dashboard/`. Por eso DevTools mostraba primero el 301 en caché y luego
la navegación insegura al puerto interno.

**Corrección:** en `frontend/nginx.conf` se añadió `absolute_redirect off` y una regla explícita
para `/dashboard` que devuelve `308 /dashboard/` relativo. Una ubicación relativa es resuelta por
el navegador contra el origen público actual, por lo que conserva `https://app.dev.local:8443`.
Se debe reconstruir y desplegar la imagen `proyecto-kubernet/frontend:dev` antes de validar.

## Paso 16 - Compatibilidad de namespaces PHP en Linux

**Incidencia:** la ruta `GET /api/v1/routine/{id}` funcionaba con `php artisan serve` en Windows,
pero en el pod Linux fallaba con `Target class ... RoutineDayController does not exist`.

**Causa y corrección:** los archivos viven en `app/Http/Controllers/API/V1`, pero algunas rutas y
declaraciones PHP usaban `Api`. Windows no distingue esas mayúsculas, mientras que Linux y el
autoloader PSR-4 sí. Se estandarizaron los imports y namespaces de los controladores afectados a
`App\\Http\\Controllers\\API\\V1`; después de reconstruir la imagen, Laravel puede resolverlos
en el contenedor.

**Validación adicional:** al consultar la ruta sin token, el manejador de excepciones intentaba
redireccionar a una ruta web `login` inexistente y producía 500. Se ajustaron `Authenticate` y
`Handler` para que cualquier ruta `api/*` responda el 401 JSON de Sanctum; con un token válido,
la petición continúa hacia `RoutineDayController`.

## Paso 17 - Recuperación y verificación de observabilidad

**Capacidad:** Docker Desktop se configuró con el backend WSL2 en 12 GiB de memoria y 2 GiB de
swap (`C:\\Users\\luisi\\.wslconfig`), seguido de `wsl --shutdown` y el reinicio de Docker
Desktop. `docker info` confirmó 12,540,850,176 bytes disponibles. Se recuperaron los perfiles
`minikube` y `proyecto-prof`; los tres nodos de producción quedaron `Ready`.

**Pila instalada:** se actualizó el release Helm `monitoring` de
`kube-prometheus-stack` 92.14.1 a revisión 3 y estado `deployed`. Prometheus (2/2), Alertmanager
(2/2), Grafana (3/3), operador, kube-state-metrics y los tres node exporters quedaron `Running`.
Grafana recibió 512 MiB de límite y un periodo de inicio ampliado para completar la carga inicial
de plugins. Metrics Server también quedó operativo: `kubectl top nodes` devolvió CPU y memoria
para los tres nodos.

**Descubrimiento:** se desactivaron los selectores implícitos de Helm para `ServiceMonitor`,
`PodMonitor` y `PrometheusRule`; Prometheus ahora descubre los recursos propios de Fitness.
Las seis alertas operativas de `fitness-operational-alerts` quedaron cargadas.

**Prueba del circuito de alertas:** las reglas controladas
`FitnessAlertPipelineValidationOne` y `FitnessAlertPipelineValidationTwo` se activaron con
`vector(1)` y se observaron activas en Prometheus y Alertmanager. Después se cambiaron a
`vector(0) > 0` —expresión sin series, necesaria porque `vector(0)` por sí sola aún dispara una
alerta— y ambas desaparecieron de Prometheus y de las alertas activas de Alertmanager.

**Pendiente delimitado:** `fitness-prod` aún no tiene MySQL, MongoDB ni `app-secrets`; por ello
los manifests de exporters no se aplicaron todavía. Antes se deben desplegar esas bases de datos
y crear credenciales de monitorización de solo lectura distintas de las de aplicación. La capa de
logs centralizados todavía no está instalada.

## Paso 18 - Bases de datos productivas y exporters

**Datos y aislamiento:** se creó `app-secrets` exclusivamente en `fitness-prod`, con material
criptográfico y contraseñas nuevos; no se reutilizaron ni se registraron valores de desarrollo.
Se aplicó la capa de MySQL, MongoDB y Redis. MySQL y MongoDB disponen de PVC `standard` de 5 GiB
independientes y los tres Pods están `Running`.

**Almacenamiento local:** el provisionador hostPath de Minikube crea los volúmenes en el control
plane. Se añadió `fsGroupChangePolicy: OnRootMismatch` para operar con UID/GID 999 sin violar
Pod Security `restricted`, y el selector de nodo quedó únicamente en el overlay productivo para
MySQL y MongoDB. Esto evita que el cambio afecte al nodo `minikube` de desarrollo.

**Credenciales de monitorización:** se crearon usuarios distintos de la aplicación. MySQL usa
`fitness_monitor` con `SELECT`, `PROCESS` y `REPLICATION CLIENT`; MongoDB usa el mismo nombre
con los roles `clusterMonitor` y `readAnyDatabase`. Sus contraseñas están solamente en los
Secrets `mysql-exporter-credentials` y `mongodb-exporter-credentials` de `fitness-prod`.

**Exporters verificados:** se aplicó `infra/observability/exporters.yaml`. Ambos Deployments,
Services y el `ServiceMonitor` están activos. Prometheus descubrió los dos targets y devolvió
`up{service="mysql-exporter"}=1`, `up{service="mongodb-exporter"}=1`, `mysql_up=1` y
`mongodb_up=1`.

## Paso 19 - Logs centralizados con Loki y Alloy

**Componentes:** se instaló Loki 3.6.16 mediante el chart `grafana/loki` 7.5.0, en modo
SingleBinary, con factor de replicación 1, autenticación desactivada sólo para este laboratorio y
un PVC `standard` de 5 GiB fijado al control plane. Se instaló Grafana Alloy 1.21.3 mediante el
chart `grafana/alloy` 1.14.4 como DaemonSet; sus tres Pods están `Running`, uno por nodo.

**Recolección y acceso:** Alloy descubre Pods con la API Kubernetes y envía al endpoint interno de
Loki exclusivamente los namespaces `fitness-prod` y `observability`. Las etiquetas conservan
`namespace`, `pod`, `container` y `app`. Se aprovisionó el datasource `Loki` en Grafana mediante
el ConfigMap `grafana-loki-datasource`; Grafana confirmó su recarga con HTTP 200.

**Validación:** Loki respondió `ready` y una consulta `{namespace="fitness-prod"}` devolvió
entradas reales de `mongodb-0`, incluidas las conexiones de `mongodb-exporter`. Esto comprueba el
recorrido completo Pod -> Alloy -> Loki -> consulta/Grafana.
