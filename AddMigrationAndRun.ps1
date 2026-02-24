# Use this when you changed your model (Models/*.cs or AppDbContext). It adds a migration then runs the backend.
# Double-click or run: powershell -ExecutionPolicy Bypass -File AddMigrationAndRun.ps1

param(
    [string]$Name = "AutoUpdate"
)

$ErrorActionPreference = "Stop"
$root = $PSScriptRoot

Set-Location $root

Write-Host "Adding migration: $Name" -ForegroundColor Cyan
$result = dotnet ef migrations add $Name --project backend 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host $result -ForegroundColor Yellow
    Write-Host "`nIf 'dotnet ef' not found, run once: dotnet tool install --global dotnet-ef" -ForegroundColor Gray
    Write-Host "Starting backend anyway..." -ForegroundColor Gray
} else {
    Write-Host "Migration added. Starting backend (migrations will apply on startup)..." -ForegroundColor Green
}

Set-Location (Join-Path $root "backend")
dotnet run
