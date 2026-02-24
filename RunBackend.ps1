# Just run the backend (apply existing migrations on startup, no new migration added).
# Double-click or run: powershell -ExecutionPolicy Bypass -File RunBackend.ps1

$backend = Join-Path $PSScriptRoot "backend"
Set-Location $backend
dotnet run
