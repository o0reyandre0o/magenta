# Packages the theme for upload via Appearance > Themes > Add New > Upload.
#
# WordPress requires style.css at the root of a single top-level folder inside
# the zip, so every entry is written under "magenta/".
#
# Why entries are written by hand instead of with a one-liner: the ZIP spec
# mandates "/" as the path separator, but on Windows PowerShell 5.1 BOTH
# Compress-Archive and ZipFile::CreateFromDirectory emit backslashes, because
# 5.1 runs on .NET Framework 4.8 where that bug was never fixed. WordPress then
# reads "magenta\style.css" as one file with a strange name sitting at the
# archive root, finds no stylesheet one level down, and rejects the upload with
# "The theme is missing the style.css stylesheet."
#
# Creating each entry explicitly is the only approach that guarantees the name
# written is the name given. The archive is reopened and checked afterwards.
#
# Dev-only files (preview, docs, tooling, git metadata) are left out - the zip
# is the production theme, not the repo.
#
# Usage:  powershell -ExecutionPolicy Bypass -File tools/build-zip.ps1

$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$root = Split-Path -Parent $PSScriptRoot
$dist = Join-Path $root 'dist'
$zip  = Join-Path $dist 'magenta.zip'

$header  = Get-Content (Join-Path $root 'style.css') -Raw
$version = if ($header -match 'Version:\s*([0-9.]+)') { $Matches[1] } else { '0.0.0' }

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

# Folders inside the include list that must never ship: the untouched client
# originals are build inputs for tools/optimize-media.ps1, not site assets.
$excludeDirs = @('_source')

# Resolve the include list to concrete files, each paired with the entry name
# it will carry inside the archive.
$files = @()
foreach ($item in $include) {
    $src = Join-Path $root $item

    if (-not (Test-Path $src)) {
        Write-Warning "missing, skipped: $item"
        continue
    }

    if (Test-Path $src -PathType Container) {
        Get-ChildItem $src -Recurse -File | ForEach-Object {
            $rel   = $_.FullName.Substring($root.Length).TrimStart('\', '/')
            $parts = $rel -split '\\'

            if ($parts | Where-Object { $excludeDirs -contains $_ }) { return }

            $files += [pscustomobject]@{
                Path  = $_.FullName
                Entry = 'magenta/' + ($rel -replace '\\', '/')
            }
        }
    } else {
        $files += [pscustomobject]@{
            Path  = $src
            Entry = "magenta/$item"
        }
    }
}

if (-not ($files | Where-Object { $_.Entry -eq 'magenta/style.css' })) {
    throw 'style.css is not in the include list - aborting.'
}

New-Item -ItemType Directory -Force -Path $dist | Out-Null
if (Test-Path $zip) { Remove-Item $zip -Force }

$stream  = [System.IO.File]::Open($zip, [System.IO.FileMode]::CreateNew)
$archive = New-Object System.IO.Compression.ZipArchive(
    $stream, [System.IO.Compression.ZipArchiveMode]::Create)

try {
    foreach ($file in $files) {
        $entry = $archive.CreateEntry(
            $file.Entry, [System.IO.Compression.CompressionLevel]::Optimal)

        $out = $entry.Open()
        $in  = [System.IO.File]::OpenRead($file.Path)
        try   { $in.CopyTo($out) }
        finally { $in.Dispose(); $out.Dispose() }
    }
} finally {
    $archive.Dispose()
    $stream.Dispose()
}

# Reopen and verify rather than trusting the write.
$check = [System.IO.Compression.ZipFile]::OpenRead($zip)
try {
    $names = $check.Entries | ForEach-Object { $_.FullName }
} finally {
    $check.Dispose()
}

$bad = $names | Where-Object { $_ -like '*\*' }
if ($bad) { throw "Backslash separators in archive: $($bad -join ', ')" }
if ($names -notcontains 'magenta/style.css') {
    throw 'magenta/style.css not found at the expected depth.'
}
$roots = $names | ForEach-Object { ($_ -split '/')[0] } | Sort-Object -Unique
if ($roots.Count -ne 1) {
    throw "Archive must have exactly one top-level folder, found: $($roots -join ', ')"
}

$size = [math]::Round((Get-Item $zip).Length / 1KB, 1)
Write-Host "Built magenta.zip  (v$version, $size KB, $($names.Count) files)"
Write-Host "Verified: single root 'magenta/', style.css present, separators conformant."
Write-Host $zip
