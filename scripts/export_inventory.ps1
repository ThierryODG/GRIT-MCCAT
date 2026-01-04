# Génère un inventaire utile pour diagnostic sans partager tout le repo
# Usage: powershell -ExecutionPolicy Bypass -File scripts\export_inventory.ps1

$root = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $root

# 1) Routes JSON (requires artisan available)
if (Test-Path "$root\artisan") {
    Write-Host "Génération de routes.json..."
    php artisan route:list --json > "$root\storage\app\diagnostic_routes.json"
} else {
    Write-Host "artisan introuvable, saute la génération des routes"
}

# 2) Liste des contrôleurs
Write-Host "Récupération des contrôleurs..."
Get-ChildItem -Recurse -Filter "*Controller.php" -Path "$root\app\Http\Controllers" | Select-Object FullName | ConvertTo-Json | Out-File "$root\storage\app\diagnostic_controllers.json"

# 3) Liste des vues
Write-Host "Récupération des vues..."
Get-ChildItem -Recurse -Include "*.blade.php" -Path "$root\resources\views" | Select-Object FullName | ConvertTo-Json | Out-File "$root\storage\app\diagnostic_views.json"

# 4) composer.json
if (Test-Path "$root\composer.json") {
    Copy-Item "$root\composer.json" "$root\storage\app\diagnostic_composer.json" -Force
}

# 5) migrations list
Get-ChildItem -Path "$root\database\migrations" -Filter "*.php" | Select-Object FullName | ConvertTo-Json | Out-File "$root\storage\app\diagnostic_migrations.json"

# 6) Créer un zip minimal
$zipPath = "$root\storage\app\sigr-its_diagnostic_$(Get-Date -Format yyyyMMddHHmmss).zip"
Compress-Archive -Path "$root\storage\app\diagnostic_*.json","$root\composer.json" -DestinationPath $zipPath -Force

Write-Host "Inventaire généré : $zipPath"
Write-Host "Fichiers:"
Get-ChildItem "$root\storage\app\diagnostic_*.json"

Write-Host "Terminé."
