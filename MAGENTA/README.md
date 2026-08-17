# Magenta — WordPress theme

Custom theme for **Magenta**, a print production and graphic design studio in
the Cayman Islands.

Built by [Toc Toc Marketing](https://toctoc.ky/).

---

## The idea

Magenta is the **M in CMYK**. The whole visual language comes out of the
mechanics of printing rather than out of photography: registration marks,
colour bars, halftone, paper grain, tape, die-cut stickers, and headlines that
land out of register and pull into alignment as you scroll.

That is a deliberate architectural decision, not just a style. **The site is
designed to be finished before a single photograph exists.** Photography drops
into named slots later without any layout changes.

---

## Stack

- Classic PHP theme, **no build step** — what is committed is what runs
- **No paid plugins.** Image slots, project metadata and the contact form are
  all native WordPress (Settings API, custom post type, `admin-ajax`)
- **No JS dependencies.** Scroll effects are ~200 lines of vanilla JS using
  `IntersectionObserver` and `requestAnimationFrame`
- English only (`inLanguage: en`)

---

## Local setup

1. Clone into your local WordPress install:

```bash
git clone <repo-url> wp-content/themes/magenta
```

2. Activate **Magenta** under Appearance → Themes.
3. Create a page, set it as the static front page under Settings → Reading.
   `front-page.php` takes over automatically.
4. Go to Settings → Permalinks and hit Save once, so `/work` and `/llms.txt`
   resolve. (The theme also flushes on activation and on version change.)

### Recommended: LocalWP

Point a LocalWP site at this repo by symlinking or cloning directly into that
site's `wp-content/themes/`.

---

## Deploying via git

The theme is set up for **[Git Updater](https://git-updater.com/)** so that
pushing to `main` offers a theme update inside wp-admin. Two headers in
`style.css` drive it:

```
GitHub Theme URI: TOCTOC-ORG/magenta-theme
Primary Branch: main
```

> **Change `TOCTOC-ORG/magenta-theme` to the real repo path before the first
> deploy.** Git Updater will not resolve updates until it matches.

Alternative if you would rather not run Git Updater: a GitHub Action that
rsyncs over SFTP on push to `main`. Say the word and it's a ten-line workflow
file.

---

## Photography: how the slot system works

Every photograph on the site is a **named slot** defined once in
`inc/media-slots.php`. That single array is simultaneously:

- the render contract used by `magenta_slot_image()`
- the admin UI at **Appearance → Magenta Media**
- the photo brief handed to whoever is shooting

Until a slot has an image assigned, it renders a halftone placeholder carrying
its own slot name, and the full shooting spec on hover. The page reads as
designed rather than broken, and anyone reviewing it can see exactly what is
outstanding.

### In a template

```php
magenta_slot_image( 'hero_main', array(
    'eager' => true,                              // above the fold only
    'sizes' => '(max-width: 900px) 92vw, 44vw',
) );
```

### Adding a slot

Add an entry to the array in `inc/media-slots.php`. The admin screen, the
progress counter and the brief all pick it up with no other changes.

### Crops — read before shooting

These are registered in `inc/setup.php` and are hard crops. If the shoot
delivers a different ratio, WordPress will cut into the frame.

| Size | Ratio | Dimensions | Used for |
|---|---|---|---|
| `magenta-4x5` | 4:5 | 1200×1500, cropped | Portraits, vertical cards |
| `magenta-16x9` | 16:9 | 1920×1080, cropped | Hero, wide banners |
| `magenta-1x1` | 1:1 | 900×900, cropped | Service tiles |
| `magenta-cut` | — | 1400 wide, uncropped | Cut-outs on white |

---

## Projects

`project` custom post type, archive at `/work`, with two taxonomies seeded on
first activation:

- **service** — Offset, Large Format, Screen Printing, Foil & Finishing,
  Packaging, Signage, Brand Identity
- **sector** — Hotels, Restaurants, Agencies, Retail, Events, Corporate

Plus a small meta box for **Client** and **Year**. The homepage grid falls back
to placeholder cards while no projects are published.

---

## Testimonials

`template-parts/home/testimonials.php` ships with **empty quotes on purpose**.
Cards render as a visible "pending" state until real, approved client quotes
are pasted in. Nothing in this theme invents a statement attributed to a named
business. Fill them via the array, or hook `magenta_testimonials`.

---

## Structured data

`inc/schema.php` prints a single JSON-LD `@graph` in `<head>` — nothing renders
visually. It contains Magenta's `Organization`/`LocalBusiness`, `WebSite` and
`WebPage` nodes, plus the canonical **TocToc** entity nodes.

The `WebSite` node credits TocToc via `creator`. Output is guarded by a static
flag so the block can never be emitted twice.

> The TocToc `@id` values are shared across every site the agency builds — that
> is what consolidates them into one entity. **Do not change them.**

Extend the graph without touching the file:

```php
add_filter( 'magenta_schema_graph', function ( $graph ) {
    $graph[] = array( '@type' => 'FAQPage', /* … */ );
    return $graph;
} );
```

---

## /llms.txt

Served from `inc/llms-txt.php` via a rewrite rule rather than a static file at
the web root, so it travels with the git sync and rebuilds itself from live
services, projects and pages. Ends with the TocToc credit line.

---

## Contact form

One `admin-ajax` endpoint (`inc/contact.php`) with nonce verification, an
off-canvas honeypot, and a 60-second per-IP rate limit held in a transient.
Mail goes to the admin email; override with:

```php
add_filter( 'magenta_contact_recipient', fn() => 'hello@magentacayman.com' );
```

`wp_mail()` on shared hosting lands in spam often enough that an SMTP route is
worth setting up before launch.

---

## File map

```
magenta/
├── style.css                    theme header + Git Updater headers
├── functions.php                bootstrap, agency credit constants
├── inc/
│   ├── setup.php                supports, image sizes, menus
│   ├── enqueue.php              assets, font detection
│   ├── media-slots.php          slot registry + admin screen + photo brief
│   ├── helpers.php              magenta_slot_image(), CMYK type, marquee…
│   ├── cpt-project.php          project CPT, taxonomies, meta box
│   ├── contact.php              AJAX contact endpoint
│   ├── schema.php               JSON-LD graph incl. TocToc entity
│   └── llms-txt.php             /llms.txt route
├── template-parts/home/         hero, ticker, services, work, process,
│                                about, testimonials, cta
├── assets/
│   ├── css/                     tokens → base → components → home
│   ├── js/                      main.js, admin-media.js
│   └── fonts/                   see fonts/README.md
├── front-page.php
├── index.php
├── header.php
└── footer.php
```

---

## Accessibility & motion

Every effect is enhancement. With JS blocked the page is complete and readable;
`[data-reveal]` elements are shown immediately rather than left hidden. All
decorative motion is skipped under `prefers-reduced-motion: reduce`, and the
CMYK plates snap to `--reg: 0`. Decorative plate duplicates are `aria-hidden`;
only one carries the real text.

---

## Still to do

- [ ] Set the real repo path in `style.css` (`GitHub Theme URI`)
- [ ] Commit the five `.woff2` files (see `assets/fonts/README.md`)
- [ ] Fill the image slots as photography arrives
- [ ] Publish real projects; the placeholder grid retires itself
- [ ] Collect and approve client quotes
- [ ] Build the Primary and Footer menus in wp-admin
- [ ] SMTP for outgoing mail
