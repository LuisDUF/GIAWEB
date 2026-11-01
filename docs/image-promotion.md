# Promoción reproducible de imágenes

El overlay `infra/overlays/prod` es el registro de aprobación de la versión. Las
imágenes de frontend y backend se fijan como referencias OCI por digest:
`host.minikube.internal:5000/fitness/<componente>@sha256:<digest>`. Un digest identifica el mismo
contenido aunque un tag posterior cambie o sea eliminado.

## Flujo de promoción

1. Construya y pruebe las imágenes candidatas en desarrollo.
2. Publique las imágenes en un registro OCI accesible al clúster y anote el
   digest que el registro devuelve. No promueva `dev`, `latest`, ni un tag de
   versión por sí solo. El laboratorio actual usa un registry `registry:2` en
   el host, publicado en el puerto 5000 y accesible desde los nodos como
   `host.minikube.internal:5000`.

   Para reconstruir ese registry y configurar los nodos del perfil, ejecute:

   ```powershell
   .\scripts\setup-local-oci-registry.ps1 -Profile proyecto-prof
   ```

   El registry usa HTTP sin autenticación exclusivamente dentro del laboratorio
   local. Para un entorno compartido, reemplace esta dirección por un registry
   con TLS, autenticación y control de acceso.
3. Actualice únicamente `digest` para frontend y backend en
   `infra/overlays/prod/kustomization.yaml`. La revisión Git que contiene esos
   valores es la aprobación de la versión.
4. Ejecute la validación, que rechaza tags y placeholders:

   ```powershell
   .\tests\infrastructure\validate.ps1 -Environment prod
   ```

5. Compruebe el cambio y promueva las capas de tráfico sin reconstruir:

   ```powershell
   .\scripts\promote-production-images.ps1 -Context proyecto-prof
   ```

   Use `-WhatIf` para validar y ver las imágenes aprobadas sin aplicar cambios.

   El script actualiza exclusivamente `frontend`, `backend` y `backend-worker`.
   No reaplica el Job `backend-migrate`, porque su plantilla es inmutable. Si
   una versión requiere migraciones, valide antes su compatibilidad, borre el
   Job finalizado y recréelo explícitamente con el mismo overlay.

6. Confirme la referencia efectiva:

   ```powershell
   kubectl --context proyecto-prof -n fitness-prod get deploy backend frontend `
     -o jsonpath='{range .items[*]}{.metadata.name}{"="}{range .spec.template.spec.containers[*]}{.image}{" "}{end}{"\n"}{end}'
   ```

## Rollback

Para retornar a una versión conocida, revierta el commit que modificó los
digests y ejecute de nuevo el script. El rollback de una imagen no revierte
migraciones: aplíquelo sólo cuando las migraciones sean compatibles hacia atrás
o exista un plan de reversión de esquema.
