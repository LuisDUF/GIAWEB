<#
.SYNOPSIS
Creates the local OCI registry used by the proyecto-prof Minikube profile.

.DESCRIPTION
The registry is published on the Docker host at port 5000. Every node resolves
that host as host.minikube.internal and receives an explicit containerd hosts.toml
for pull/resolve over HTTP. This is suitable for the isolated laboratory only;
use TLS and authentication for a shared or Internet-facing registry.
#>
[CmdletBinding()]
param(
    [string]$Profile = 'proyecto-prof',
    [string]$RegistryContainer = 'fitness-oci-registry',
    [int]$Port = 5000
)

$ErrorActionPreference = 'Stop'
$volume = "$RegistryContainer-data"
$running = docker container inspect --format '{{.State.Running}}' $RegistryContainer 2>$null

if ($LASTEXITCODE -ne 0) {
    docker volume create $volume | Out-Null
    docker run -d --name $RegistryContainer --restart unless-stopped -p "${Port}:5000" -v "${volume}:/var/lib/registry" registry:2 | Out-Null
} elseif ($running -ne 'true') {
    docker start $RegistryContainer | Out-Null
}

$registryHost = "host.minikube.internal:$Port"
$hostsToml = @"
server = "http://$registryHost"

[host."http://$registryHost"]
  capabilities = ["pull", "resolve"]
  skip_verify = true
"@
$encoded = [Convert]::ToBase64String([Text.Encoding]::UTF8.GetBytes($hostsToml))

$nodes = minikube --profile $Profile node list | ForEach-Object {
    ($_ -split '\s+')[0]
}
if ($LASTEXITCODE -ne 0 -or -not $nodes) {
    throw "Could not list nodes for Minikube profile '$Profile'."
}

foreach ($node in $nodes) {
    $remote = "sudo mkdir -p /etc/containerd/certs.d/$registryHost; echo $encoded | base64 -d | sudo tee /etc/containerd/certs.d/$registryHost/hosts.toml >/dev/null; curl -fsS http://$registryHost/v2/ >/dev/null"
    minikube --profile $Profile ssh --node $node -- $remote
    if ($LASTEXITCODE -ne 0) {
        throw "Could not configure or reach the OCI registry from node '$node'."
    }
}

Write-Host "OCI registry ready at $registryHost for profile $Profile."
