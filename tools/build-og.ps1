# Renders the Open Graph / Twitter share card at 1200x630.
#
# Composed with ffmpeg rather than by hand so it can be regenerated whenever the
# strapline or the featured photograph changes.
#
# The wordmark is the real logo, rasterised from the brand kit to
# assets/img/social/logo-og.png, rather than the name re-set in Impact. The
# previous card drew it four times in cyan, yellow, black and magenta as an
# out-of-register separation - a print-shop device the studio's identity does
# not use, on a card that also advertised offset, packaging and signage.
#
# Only Consolas is needed now, for the small mono lines.
#
# Usage:  powershell -ExecutionPolicy Bypass -File tools/build-og.ps1

$ErrorActionPreference = 'Stop'

if (-not (Get-Command ffmpeg -ErrorAction SilentlyContinue)) {
    throw 'ffmpeg is not on PATH.'
}

$root   = Split-Path -Parent $PSScriptRoot
$outDir = Join-Path $root 'assets\img\social'
$photo  = Join-Path $root 'assets\img\_source\coffee-cart-cards.jpg'
$logo   = Join-Path $outDir 'logo-og.png'
$out    = Join-Path $outDir 'og-default.jpg'

New-Item -ItemType Directory -Force -Path $outDir | Out-Null
if (-not (Test-Path $photo)) { throw "featured photo missing: $photo" }
if (-not (Test-Path $logo))  { throw "logo raster missing: $logo" }

# ffmpeg needs the drive colon escaped inside a filter expression.
function Convert-FontPath([string]$name) {
    $p = Join-Path $env:WINDIR "Fonts\$name"
    if (-not (Test-Path $p)) { throw "font not found: $p" }
    return ($p -replace '\\', '/') -replace '^([A-Za-z]):', '$1\:'
}

$mono = Convert-FontPath 'consola.ttf'

# Brand palette, sampled from the kit's SVGs.
$paper   = '0xFDFEFF'
$ink     = '0x333333'
$magenta = '0xEC008C'
$teal    = '0x2BA48C'
$yellow  = '0xFFF100'

$filters = New-Object System.Collections.Generic.List[string]
$step    = 0

function Add-Filter([string]$f) {
    $script:step++
    $inLabel  = if ($script:step -eq 1) { '[base]' } else { "[s$($script:step - 1)]" }
    $filters.Add("$inLabel$f[s$($script:step)]")
}

# Photo panel down the right-hand third, with a magenta keyline against it.
$filters.Add('[1:v]scale=470:630:force_original_aspect_ratio=increase,crop=470:630[photo]')
$filters.Add('[0:v][photo]overlay=730:0[base]')

Add-Filter "drawbox=x=722:y=0:w=8:h=630:color=$($magenta):t=fill"

# Three brand marks along the bottom edge instead of a press control strip.
Add-Filter "drawbox=x=60:y=590:w=54:h=8:color=$($magenta):t=fill"
Add-Filter "drawbox=x=124:y=590:w=54:h=8:color=$($teal):t=fill"
Add-Filter "drawbox=x=188:y=590:w=54:h=8:color=$($yellow):t=fill"

Add-Filter "drawtext=fontfile='$mono':text='DESIGN \& PRODUCTION STUDIO':fontcolor=$($magenta):fontsize=21:x=60:y=74"

# Separators are ASCII on purpose: ffmpeg reads the filter script as Latin-1,
# so a UTF-8 middot arrives as mojibake.
Add-Filter "drawtext=fontfile='$mono':text='Design / Print / Framing / Fine art':fontcolor=$($ink)@0.72:fontsize=22:x=62:y=492"
Add-Filter "drawtext=fontfile='$mono':text='GRAND CAYMAN, CAYMAN ISLANDS':fontcolor=$($ink)@0.55:fontsize=21:x=62:y=538"

# The logo last so it sits above everything, then the strapline under it.
$filters.Add("[s$($step)][2:v]overlay=58:180[s$($step + 1)]")
$step++

Add-Filter "drawtext=fontfile='$mono':text='From idea to finished piece.':fontcolor=$($ink):fontsize=34:x=62:y=432"

# Terminate the chain on the default output label.
$filters[$filters.Count - 1] = $filters[$filters.Count - 1] -replace '\[s\d+\]$', '[out]'

$scriptPath = Join-Path $env:TEMP 'magenta-og-filter.txt'
$encoding   = New-Object System.Text.ASCIIEncoding
[System.IO.File]::WriteAllText($scriptPath, ($filters -join ";`n"), $encoding)

& ffmpeg -y -v error `
    -f lavfi -i "color=c=$($paper):s=1200x630" `
    -i $photo `
    -i $logo `
    -filter_complex_script $scriptPath `
    -map '[out]' -frames:v 1 -q:v 3 $out

Remove-Item $scriptPath -Force

if (-not (Test-Path $out)) { throw 'og-default.jpg was not written.' }

$size = [math]::Round((Get-Item $out).Length / 1KB)
Write-Host "Built og-default.jpg  (1200x630, $size KB)"
