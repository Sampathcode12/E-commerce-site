# Add a new migration (from C# model) and optionally update the database.
# Run from repo root: powershell -ExecutionPolicy Bypass -File backend\AddMigrationAndUpdateDb.ps1 [MigrationName]
# Or from backend folder: .\AddMigrationAndUpdateDb.ps1 [MigrationName]
# If MigrationName is omitted, uses "InitialCreate" for first time or "UpdateSchema" for new migrations.

param(
    [string]$MigrationName = "UpdateSchema"
)

$backendDir = if ($PSScriptRoot) { $PSScriptRoot } else { Split-Path -Parent $MyInvocation.MyCommand.Path }
Set-Location $backendDir

Write-Host "Adding migration: $MigrationName (from C# model)..." -ForegroundColor Cyan
dotnet ef migrations add $MigrationName
if ($LASTEXITCODE -ne 0) {
    Write-Host "Failed to add migration. Ensure backend is not running and dotnet-ef is installed: dotnet tool install --global dotnet-ef" -ForegroundColor Red
    exit 1
}

Write-Host "`nTo apply migrations and update __EFMigrationsHistory:" -ForegroundColor Cyan
Write-Host "  Option A: Run the backend (dotnet run or F5) - migrations apply on startup." -ForegroundColor Yellow
Write-Host "  Option B: Run: dotnet ef database update" -ForegroundColor Yellow
$apply = Read-Host "Apply database update now? (y/n)"
if ($apply -eq 'y') {
    dotnet ef database update
}
