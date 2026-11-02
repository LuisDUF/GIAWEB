param(
    [ValidateSet('dev', 'prod')]
    [string]$Environment = 'dev'
)

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent (Split-Path -Parent $PSScriptRoot)
$overlay = Join-Path $root "infra/overlays/$Environment"

$rendered = (kubectl kustomize $overlay) -join "`n"
if ($LASTEXITCODE -ne 0) {
    throw "Kustomize render failed for $Environment."
}

foreach ($expected in @(
    'name: mysql-backup',
    'name: mongodb-backup',
    'name: allow-backup-object-storage',
    'name: allow-observability-scrape',
    'image: rclone/rclone:1.69.1',
    'secretName: backup-object-storage'
)) {
    if ($rendered -notmatch [regex]::Escape($expected)) {
        throw "Rendered $Environment overlay is missing required backup control: $expected"
    }
}

if ($rendered -match 'name: database-backups') {
    throw 'Backups must use encrypted remote object storage, not a local database-backups PVC.'
}

Write-Host "Kustomize render passed for $Environment."
