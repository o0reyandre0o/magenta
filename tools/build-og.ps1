# Renders the Open Graph / Twitter share card at 1200x630.
#
# Composed with ffmpeg rather than by hand so it can be regenerated whenever
# the wordmark, the strapline or the featured photograph changes.
#
# The type is set in Impact, which is the first system fallback in the theme's
# --font-display stack - so the card and the site read as the same voice even
# before the licensed webfont is committed.
#
# The wordmark is drawn four times in cyan, yellow, black and magenta at slight
# offsets: the same out-of-register separation the hero animates, frozen at the
# moment before the plates align.
#
# Usage:  powershell -ExecutionPolicy Bypass -File tools/build-og.ps1

$ErrorActionPreference = 'Stop'

if (-not (Get-Command ffmpeg -ErrorAction SilentlyContinue)) {
    throw 'ffmpeg is not on PATH.'
}

$root   = Split-Path -Parent $PSScriptRoot
$outDir = Join-Path $root 'assets\img\social'
$photo  = Join-Path $root 'assets\img\_source\coffee-cart-cards.jpg'
$out    = Join-Path $outDir 'og-default.jpg'

New-Item -ItemType Directory -Force -Path $outDir | Out-Null
if (-not (Test-Path $photo)) { throw "featured photo missing: $photo" }

# ffmpeg needs the drive colon escaped inside a filter expression.
function Convert-FontPath([string]$name) {
    $p = Join-Path $env:WINDIR "Fonts\$name"
    if (-not (Test-Path $p)) { throw "font not found: $p" }
    return ($p -replace '\\', '/') -replace '^([A-Za-z]):', '$1\:'
}

$display = Convert-FontPath 'impact.ttf'
$mono    = Convert-FontPath 'consola.ttf'

$paper   = '0xFDFEFF'
$ink     = '0x0D0D0D'
$magenta = '0xEA028C'
$cyan    = '0x00AEEF'
$yellow  = '0xFFE800'

$filters = New-Object System.Collections.Generic.List[string]

# Photo panel down the right-hand third, with a magenta keyline against it.
$filters.Add('[1:v]scale=470:630:force_original_aspect_ratio=increase,crop=470:630[photo]')
$filters.Add('[0:v][photo]overlay=730:0[base]')
$filters.Add("[base]drawbox=x=724:y=0:w=6:h=630:color=$($magenta):t=fill[keyed]")

$chain = '[keyed]'
$step  = 0
function Add-Filter([string]$f) {
    $script:step++
    $label = "[s$script:step]"
    $script:filters.Add("$script:chain$f$label")
    $script:chain = $label
}

# Colour bar along the bottom edge - the press sheet's control strip.
$ramp = @($cyan, $magenta, $yellow, $ink)
for ($i = 0; $i -lt 40; $i++) {
    $x = $i * 30
    Add-Filter "drawbox=x=$($x):y=610:w=30:h=20:color=$($ramp[$i % 4]):t=fill"
}

# Registration cross, top left, outside the "trim".
Add-Filter "drawbox=x=56:y=52:w=2:h=40:color=$($ink)@0.35:t=fill"
Add-Filter "drawbox=x=37:y=71:w=40:h=2:color=$($ink)@0.35:t=fill"

Add-Filter "drawtext=fontfile='$mono':text='PRINT PRODUCTION \& GRAPHIC DESIGN':fontcolor=$($ink)@0.62:fontsize=21:x=100:y=62"

# Wordmark, out of register.
$plates = @(
    @{ C = $cyan;    X = 50; Y = 188 },
    @{ C = $yellow;  X = 66; Y = 178 },
    @{ C = $ink;     X = 61; Y = 194 },
    @{ C = $magenta; X = 58; Y = 184 }
)
foreach ($p in $plates) {
    Add-Filter "drawtext=fontfile='$display':text='MAGENTA':fontcolor=$($p.C):fontsize=150:x=$($p.X):y=$($p.Y)"
}

Add-Filter "drawtext=fontfile='$display':text='WE PUT INK ON THE ISLAND.':fontcolor=$($ink):fontsize=52:x=58:y=372"
Add-Filter "drawtext=fontfile='$mono':text='Menus \· Packaging \· Signage \· Identity':fontcolor=$($ink)@0.66:fontsize=22:x=60:y=452"
Add-Filter "drawtext=fontfile='$mono':text='GRAND CAYMAN \· CAYMAN ISLANDS':fontcolor=$($magenta):fontsize=21:x=60:y=524"

# Terminate the chain on the default output label.
$filters[$filters.Count - 1] = $filters[$filters.Count - 1] -replace '\[s\d+\]$', '[out]'

$scriptPath = Join-Path $env:TEMP 'magenta-og-filter.txt'
[System.IO.File]::WriteAllText($scriptPath, ($filters -join ";`n"))

& ffmpeg -y -v error `
    -f lavfi -i "color=c=$($paper):s=1200x630" `
    -i $photo `
    -filter_complex_script $scriptPath `
    -map '[out]' -frames:v 1 -q:v 3 $out

Remove-Item $scriptPath -Force

if (-not (Test-Path $out)) { throw 'og-default.jpg was not written.' }

$probe = & ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=p=0 $out
if ($probe.Trim() -ne '1200,630') { throw "unexpected dimensions: $probe" }

$size = [math]::Round((Get-Item $out).Length / 1KB, 1)
Write-Host "Built og-default.jpg  (1200x630, $size KB)"
Write-Host $out
