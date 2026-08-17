# Optimises the client-supplied photos and reels into web-ready derivatives.
#
# Requires ffmpeg on PATH.
#
# Sources live in assets/img/_source/ and are never shipped - build-zip.ps1
# skips that folder. Everything the site actually loads is generated here, so
# the pipeline can be re-run whenever better originals arrive.
#
# Photos  -> assets/img/work/<slug>-{480,900}.webp
# Reels   -> assets/video/<slug>.{mp4,webm} + <slug>-poster.webp
#
# Nothing is ever upscaled: the width ladder is clipped to the source width, so
# a small original yields fewer files rather than a soft, inflated one.
#
# Usage:  powershell -ExecutionPolicy Bypass -File tools/optimize-media.ps1

$ErrorActionPreference = 'Stop'

if (-not (Get-Command ffmpeg -ErrorAction SilentlyContinue)) {
    throw 'ffmpeg is not on PATH.'
}

$root   = Split-Path -Parent $PSScriptRoot
$source = Join-Path $root 'assets\img\_source'
$work   = Join-Path $root 'assets\img\work'
$video  = Join-Path $root 'assets\video'

foreach ($dir in @($work, $video)) {
    New-Item -ItemType Directory -Force -Path $dir | Out-Null
}

# --------------------------------------------------------------- Photographs
# Cropped to 4:5 to match the work-card slot. Centre crop: these are flat lays,
# the subject sits in the middle of the frame.
$photos = @(
    @{ File = 'lalique-brochures.jpg';        Slug = 'lalique-brochures' },
    @{ File = 'align-brochures.jpg';          Slug = 'align-brochures' },
    @{ File = 'goddess-beer.jpg';             Slug = 'goddess-beer' },
    @{ File = 'livia-nicola-book.jpg';        Slug = 'livia-nicola-book' },
    @{ File = 'amuse-bouche-menu.jpg';        Slug = 'amuse-bouche-menu' },
    @{ File = 'anytime-wellness-cards.jpg';   Slug = 'anytime-wellness-cards' },
    @{ File = 'coffee-cart-cards.jpg';        Slug = 'coffee-cart-cards' }
)

$widths = @(480, 900)
$made   = 0

foreach ($photo in $photos) {
    $src = Join-Path $source $photo.File
    if (-not (Test-Path $src)) { Write-Warning "missing source: $($photo.File)"; continue }

    $probe = & ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=p=0 $src
    $sw = [int]($probe -split ',')[0]

    foreach ($w in $widths) {
        if ($w -gt $sw) { Write-Warning "skip $($photo.Slug)-$w (source only ${sw}px wide)"; continue }

        $h   = [int][math]::Round($w * 5 / 4)
        $out = Join-Path $work "$($photo.Slug)-$w.webp"

        # Scale so the short edge covers, then centre-crop to exactly w x h.
        & ffmpeg -y -v error -i $src `
            -vf "scale=$($w):$($h):force_original_aspect_ratio=increase,crop=$($w):$($h)" `
            -c:v libwebp -quality 74 -compression_level 6 -preset photo `
            $out
        $made++
    }
}

# --------------------------------------------------------------------- Reels
# Muted, looping, autoplaying decoration: audio is stripped entirely and the
# poster carries the first frame so nothing pops in blank.
$reels = @(
    @{ File = 'vinyl-stickers.mp4'; Slug = 'vinyl-stickers' },
    @{ File = 'festive-diecut.mp4'; Slug = 'festive-diecut' },
    @{ File = 'wellness-gift.mp4';  Slug = 'wellness-gift' }
)

foreach ($reel in $reels) {
    $src = Join-Path $source $reel.File
    if (-not (Test-Path $src)) { Write-Warning "missing source: $($reel.File)"; continue }

    $mp4    = Join-Path $video "$($reel.Slug).mp4"
    $webm   = Join-Path $video "$($reel.Slug).webm"
    $poster = Join-Path $video "$($reel.Slug)-poster.webp"

    # Cap the long edge at 1080: these display in a narrow column, and two of
    # the three originals are well below that already.
    $scale = "scale='min(720,iw)':-2"

    & ffmpeg -y -v error -i $src -an -vf $scale `
        -c:v libx264 -profile:v main -crf 30 -preset slow -pix_fmt yuv420p `
        -movflags +faststart $mp4

    & ffmpeg -y -v error -i $src -an -vf $scale `
        -c:v libvpx-vp9 -crf 38 -b:v 0 -row-mt 1 -deadline good -cpu-used 2 `
        $webm

    & ffmpeg -y -v error -i $src -frames:v 1 -vf $scale `
        -c:v libwebp -quality 70 $poster

    $made += 3
}

Write-Host "Generated $made files."
Write-Host ''
Write-Host 'Photos:'
Get-ChildItem $work -File | Sort-Object Name | ForEach-Object {
    "  {0,-34} {1,7:N0} KB" -f $_.Name, ($_.Length / 1KB)
}
Write-Host ''
Write-Host 'Video:'
Get-ChildItem $video -File | Sort-Object Name | ForEach-Object {
    "  {0,-34} {1,7:N0} KB" -f $_.Name, ($_.Length / 1KB)
}

$total = (Get-ChildItem $work, $video -File | Measure-Object -Property Length -Sum).Sum
Write-Host ''
Write-Host ("Shipped weight: {0:N2} MB" -f ($total / 1MB))
