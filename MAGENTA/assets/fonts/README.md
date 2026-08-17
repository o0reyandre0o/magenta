# Fonts

Drop the `.woff2` files listed below into this folder and commit them. The
theme detects them automatically — `inc/enqueue.php` switches from the Google
Fonts CDN to `assets/css/fonts.css`, and preloads the two faces that paint
above the fold. No code change needed.

| File | Family | Used for |
|---|---|---|
| `anton-v25-latin-regular.woff2` | Anton | Display headlines, the CMYK separation |
| `archivo-v19-latin-regular.woff2` | Archivo | Body copy |
| `archivo-v19-latin-700.woff2` | Archivo Bold | Emphasis |
| `space-mono-v13-latin-regular.woff2` | Space Mono | Eyebrows, buttons, technical data |
| `caveat-v18-latin-regular.woff2` | Caveat | Handwritten annotations, signature |

Easiest source is [google-webfonts-helper](https://gwfh.mranftl.com/fonts) —
pick the family, `latin` subset, `woff2` only, and it hands back files already
named in this pattern. If your download names them differently, either rename
them or update `MAGENTA_LOCAL_FONT_FILES` in `inc/enqueue.php` and the
`@font-face` blocks in `assets/css/fonts.css`.

All five are open licence (Anton, Archivo, Space Mono, Caveat are SIL OFL), so
self-hosting is permitted.

## Swapping the display face

Anton is a stand-in with the right weight and width. If the budget stretches to
a licensed face, the design was drawn with condensed grotesques in mind —
Druk, Knockout, Monument Extended and similar all drop straight in. Change
`--font-display` in `assets/css/tokens.css` and nothing else moves.
