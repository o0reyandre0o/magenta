# Packages the theme for upload via Appearance > Themes > Add New > Upload.
#
# WordPress requires style.css at the root of a single top-level folder inside
# the zip, so the files are staged into dist/magenta/ first.
#
# Dev-only files (preview, docs, tooling, git metadata) are left out — the zip
# is the production theme, not the repo.
#
# Usage:  powershell -ExecutionPolicy Bypass -File tools/build-zip.ps1

$ErrorActionPreference = 'Stop'

$root  = Split-Path -Parent $PSScriptRoot
$dist  = Join-Path $root 'dist'
$stage = Join-Path $dist 'magenta'
$zip   = Join-Path $dist 'magenta.zip'

# Read the version out of the theme header so the zip is always labelled.
$header  = Get-Content (Join-Path $root 'style.css') -Raw
$version = if ($header -match 'Version:\s*([0-9.]+)') { $Matches[1] } else { '0.0.0' }

if (Test-Path $stage) { Remove-Item $stage -Recurse -Force }
if (Test-Path $zip)   { Remove-Item $zip -Force }
New-Item -ItemType Directory -Force -Path $stage | Out-Null

$include = @(
    'style.css',
    'functions.php',
    'index.php',
    'header.php',
    'footer.php',
    'front-page.php',
    'README.md',
    'inc',
    'template-parts',
    'assets'
)

foreach ($item in $include) {
    $src = Join-Path $root $item
    if (Test-Path $src) {
        Copy-Item $src -Destination $stage -Recurse -Force
    } else {
        Write-Warning "missing, skipped: $item"
    }
}

Compress-Archive -Path $stage -DestinationPath $zip -CompressionLevel Optimal
Remove-Item $stage -Recurse -Force

$size = [math]::Round((Get-Item $zip).Length / 1KB, 1)
Write-Host "Built magenta.zip  (v$version, $size KB)"
Write-Host $zip
