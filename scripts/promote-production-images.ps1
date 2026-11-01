<#
.SYNOPSIS
Promotes exactly the images locked in infra/overlays/prod to a Kubernetes cluster.

.DESCRIPTION
This script never builds or retags an image. The production Kustomize overlay is
the approval record: application images must be `name@sha256:<digest>`. Before
applying it, the script validates that the rendered manifests contain only those
immutable references and waits for the public workloads.

The digest image references must already be available to the target cluster's
runtime or to its configured OCI registry. For a clean cluster, publish those
exact digest references to an OCI registry first; do not replace them with tags.
#>
[CmdletBinding(SupportsShouldProcess)]
param(
    [string]$Context = 'proyecto-prof',
    [string]$Namespace = 'fitness-prod',
    [switch]$SkipWait
)

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot
$overlay = Join-Path $root 'infra/overlays/prod'
$validator = Join-Path $root 'tests/infrastructure/validate.ps1'

& $validator -Environment prod
if ($LASTEXITCODE -ne 0) {
    throw 'Refusing promotion because production manifest validation failed.'
}

$rendered = (kubectl kustomize $overlay) -join "`n"
$images = [regex]::Matches(
    $rendered,
    '(?m)^\s*(?:-\s*)?image:\s*(host\.minikube\.internal:5000/fitness/(?:frontend|backend)@sha256:[a-f0-9]{64})\s*$'
) | ForEach-Object { $_.Groups[1].Value } | Sort-Object -Unique

if ($images.Count -ne 2) {
    throw "Expected exactly two locked application image digests; found $($images.Count)."
}

Write-Host "Approved immutable artifacts:"
$images | ForEach-Object { Write-Host "  $_" }

$backendImage = $images | Where-Object { $_ -match '/backend@' }
$frontendImage = $images | Where-Object { $_ -match '/frontend@' }

# No se reaplica todo el overlay: el Job de migración tiene una plantilla
# inmutable y debe borrarse/recrearse explícitamente sólo si la versión lo
# requiere. Esta operación cambia exclusivamente las capas que sirven tráfico.
$updates = @(
    @{ Deployment = 'frontend'; Container = 'frontend'; Image = $frontendImage }
    @{ Deployment = 'backend'; Container = 'php-fpm'; Image = $backendImage }
    @{ Deployment = 'backend-worker'; Container = 'worker'; Image = $backendImage }
)

foreach ($update in $updates) {
    $target = "$Context/$Namespace deployment/$($update.Deployment)"
    $action = "set $($update.Container) to $($update.Image)"
    if ($PSCmdlet.ShouldProcess($target, $action)) {
        kubectl --context $Context -n $Namespace set image "deployment/$($update.Deployment)" "$($update.Container)=$($update.Image)"
        if ($LASTEXITCODE -ne 0) {
            throw "Image promotion failed for deployment/$($update.Deployment)."
        }
    }
}

if (-not $SkipWait) {
    kubectl --context $Context -n $Namespace rollout status deployment/frontend --timeout=5m
    kubectl --context $Context -n $Namespace rollout status deployment/backend --timeout=5m
}
