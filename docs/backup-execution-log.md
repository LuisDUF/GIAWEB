# Registro de ejecución de respaldos

## 1 de noviembre de 2026

Este registro conserva resultados observados y no sustituye las evidencias de
restauración aislada requeridas para declarar RPO/RTO cumplidos. No contiene
credenciales, contenido de dumps ni configuración de `rclone`.

| Ambiente | Acción | Resultado | Evidencia operativa |
| --- | --- | --- | --- |
| `fitness-dev` | Verificación de `backup-object-storage` | Correcto | El Secret y su clave `rclone.conf` ya existían; no se rotaron ni se imprimieron. |
| `fitness-dev` | Aplicación de overlay | Correcto | Se configuraron CronJobs, `backup-policy`, `backup-scripts` y las políticas `allow-backup-object-storage` y `allow-observability-scrape`. |
| `fitness-dev` | Primeros Jobs manuales | Fallido y corregido | Los dumps iniciaron, pero `encrypt-upload-prune` falló por el script con CRLF montado en un contenedor Linux. |
| `fitness-dev` | Corrección | Correcto | `upload-and-prune.sh` se normalizó a LF y se actualizó el ConfigMap `backup-scripts`. Un Job de sólo lectura confirmó acceso a `backup-crypt:`. |
| `fitness-dev` | MySQL manual | Correcto | Job `mysql-backup-manual-20261101142915`: `Complete 1/1`, duración 39 s. |
| `fitness-dev` | MongoDB manual | Correcto | Job `mongodb-backup-manual-20261101143041`: `Complete 1/1`, duración 4 s. |
| `fitness-prod` | Cuota y red | Correcto | `limits.cpu` se elevó de 8 a 14 tras autorización para permitir HPA y rollout con `maxSurge`; se aplicó `allow-observability-scrape`. |
| `proyecto-prof` | Recuperación del control plane | Recuperado con incidente residual | Se reinició el perfil Docker con `minikube start --profile proyecto-prof --driver=docker`. El API server y los tres nodos volvieron a `Ready`; `calico-kube-controllers` quedó en `CrashLoopBackOff` y requiere seguimiento independiente. |
| `fitness-prod` | MySQL manual | Correcto | Job `mysql-backup-manual-20261101143131`: `Complete 1/1`, duración 9 min 43 s. |
| `fitness-prod` | MongoDB manual | Correcto | Job `mongodb-backup-manual-20261101144140`: `Complete 1/1`, duración 2 min 21 s; `mongodump` registró 3 documentos de `activity_logs`. |
| `fitness-prod` | Restauración aislada MySQL | Correcto | `mysql-restore-20261101154123`: checksum correcto e importación completada en 42 s. Validación: 18 tablas, `CHECK TABLE activity_outbox` = `OK`, 3 registros. RPO observado 59 min 43 s; RTO técnico conservador 2 min 15.9 s. |
| `fitness-prod` | Restauración aislada MongoDB | Correcto | `mongodb-restore-20261101154319`: checksum correcto, 3 documentos restaurados sin fallos y `activity_event_id_unique` único. RPO observado 1 h 00 min 06 s; RTO técnico 50.2 s. |
| `fitness-prod` | Matriz de integridad D09 | Correcto | Se verificaron descarga cifrada, SHA-256, importación autenticada por Services aislados, conteos, tabla MySQL `activity_outbox` e índice MongoDB `activity_event_id_unique`. La prueba de aplicación HTTP no se desplegó para conservar el aislamiento sin escrituras; se registró explícitamente como alcance de la prueba. |

## Estado de aceptación

Los respaldos de desarrollo y producción están validados a nivel de ejecución y
carga. La restauración aislada de MySQL y MongoDB de producción está validada,
incluidos checksum, datos e índices; los RPO/RTO medidos cumplen las metas de 24 h
y 30 min. Un segundo Job MySQL duplicado falló al detectar una tabla existente en
el destino ya recuperado; se conserva como evidencia de que no se debe repetir una
importación sobre la misma base restaurada y no afectó al Job correcto ni al origen.
