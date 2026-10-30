param(
    [ValidateSet('dev', 'prod')]
    [string]$Environment = 'dev'
)

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent (Split-Path -Parent $PSScriptRoot)
$overlay = Join-Path $root "infra/overlays/$Environment"

kubectl kustomize $overlay | Out-Null
if ($LASTEXITCODE -ne 0) {
    throw "Kustomize render failed for $Environment."
}

Write-Host "Kustomize render passed for $Environment."
