# Proyecto Kubernet - Fitness

Aplicación multicapa de rutinas y sesiones de entrenamiento: frontend Astro, API Laravel,
MySQL como fuente de verdad y MongoDB como bitácora de actividad. La infraestructura se
define con Kustomize para dos ambientes aislados. El laboratorio de desarrollo se ejecuta en
Minikube; producción se conserva como overlay para un clúster independiente.

## Ambientes

| Ambiente | Contexto | Namespace | URLs |
| --- | --- | --- | --- |
| Desarrollo | `minikube` | `fitness-dev` | `https://app.dev.local`, `https://api.dev.local` |
| Producción de laboratorio | `proyecto-prod` | `fitness-prod` | `https://app.prod.local`, `https://api.prod.local` |

No se comparten PVC, Secrets, datos ni credenciales entre ambos clústeres.

## Inicio rápido

1. Lea [arquitectura](docs/architecture.md) y [secrets](docs/secrets-and-deployment.md).
2. Inicie Minikube con driver Docker y confirme `kubectl --context minikube get nodes`.
3. Construya e importe las imágenes al runtime de Minikube, según la guía de despliegue.
4. Cree los Secrets exclusivamente en el namespace del ambiente.
5. Revise y aplique el overlay: `kubectl --context minikube apply -k infra/overlays/dev`.

Los comandos exactos, resultados de validación y límites conocidos están en
[docs/implementation-log.md](docs/implementation-log.md).
