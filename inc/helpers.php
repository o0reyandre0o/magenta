<?php
/**
 * Template helpers.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The client's own photographed jobs, shipped with the theme.
 *
 * Real work already produced, so the site shows genuine output from launch
 * rather than placeholders. Shared by the work grid and the hero slider -
 * publishing a `project` post replaces the grid, but the hero keeps using
 * these because they are known-good, cleared images.
 *
 * `alt` describes the photograph for anyone who cannot see it. It is not a
 * repeat of the title.
 *
 * @return array<int, array<string, string>>
 */
function magenta_work_items(): array {
	return apply_filters(
		'magenta_work_items',
		array(
			array(
				'slug'   => 'coffee-cart-cards',
				'title'  => __( 'The Coffee Cart', 'magenta' ),
				'client' => __( 'Mobile coffee &amp; matcha', 'magenta' ),
				'meta'   => __( 'Business cards &middot; Uncoated stock', 'magenta' ),
				'alt'    => __( 'Two stacks of square business cards, one black and one cream, showing a retro running coffee-cup mascot.', 'magenta' ),
			),
			array(
				'slug'   => 'amuse-bouche-menu',
				'title'  => __( 'Amuse Bouche', 'magenta' ),
				'client' => __( 'Restaurant', 'magenta' ),
				'meta'   => __( 'Tasting menu &middot; Die-cut &middot; Coloured stock', 'magenta' ),
				'alt'    => __( 'A tasting menu standing open beside a red die-cut envelope closed with a small paper heart.', 'magenta' ),
			),
			array(
				'slug'   => 'goddess-beer',
				'title'  => __( 'Goddess Hazy IPA', 'magenta' ),
				'client' => __( '19&middot;81 Brewing Co.', 'magenta' ),
				'meta'   => __( 'Coasters &middot; Die-cut &middot; Brochure', 'magenta' ),
				'alt'    => __( 'Round pink beer coasters and a matching folded brochure for a collaborative brew.', 'magenta' ),
			),
			array(
				'slug'   => 'anytime-wellness-cards',
				'title'  => __( 'Anytime Wellness', 'magenta' ),
				'client' => __( 'Wellness', 'magenta' ),
				'meta'   => __( 'Gift cards &middot; Gold foil', 'magenta' ),
				'alt'    => __( 'Stacks of white gift cards stamped in gold foil, styled with dried flowers and a wooden salt scoop.', 'magenta' ),
			),
			array(
				'slug'   => 'lalique-brochures',
				'title'  => __( '60 Lalique', 'magenta' ),
				'client' => __( 'Property', 'magenta' ),
				'meta'   => __( 'Property brochure &middot; Perfect bound', 'magenta' ),
				'alt'    => __( 'A fanned stack of property brochures for a Crystal Harbour home, opened to interior photography.', 'magenta' ),
			),
			array(
				'slug'   => 'align-brochures',
				'title'  => __( 'Align', 'magenta' ),
				'client' => __( 'Healthcare', 'magenta' ),
				'meta'   => __( 'Brochure suite &middot; Saddle stitched', 'magenta' ),
				'alt'    => __( 'A spread of purple and white healthcare brochures for adult and paediatric therapy services.', 'magenta' ),
			),
			array(
				'slug'   => 'vinyl-stickers',
				'video'  => true,
				'title'  => __( 'Vinyl stickers', 'magenta' ),
				'client' => __( 'Stickers &amp; labels', 'magenta' ),
				'meta'   => __( 'Contour cut to shape', 'magenta' ),
				'alt'    => __( 'Vinyl stickers being weeded and peeled after contour cutting.', 'magenta' ),
			),
			array(
				'slug'   => 'festive-diecut',
				'video'  => true,
				'title'  => __( 'Festive die-cut', 'magenta' ),
				'client' => __( 'Seasonal', 'magenta' ),
				'meta'   => __( 'Printed &amp; cut in house', 'magenta' ),
				'alt'    => __( 'Custom die-cut seasonal pieces stacked and fanned out on a shelf.', 'magenta' ),
			),
			array(
				'slug'   => 'wellness-gift',
				'video'  => true,
				'title'  => __( 'Gift card boxing', 'magenta' ),
				'client' => __( 'Wellness', 'magenta' ),
				'meta'   => __( 'Foiled &amp; boxed by hand', 'magenta' ),
				'alt'    => __( 'Foiled wellness gift cards being handled and arranged.', 'magenta' ),
			),
			array(
				'slug'   => 'livia-nicola-books',
				'title'  => __( 'Livia Nicola', 'magenta' ),
				'client' => __( 'Author', 'magenta' ),
				'meta'   => __( 'Book &middot; Perfect bound', 'magenta' ),
				'alt'    => __( 'A stack of softcover books with an ocean photograph wrapping the cover.', 'magenta' ),
			),
			array(
				'slug'   => 'livia-nicola-spread',
				'title'  => __( 'Livia Nicola &mdash; spread', 'magenta' ),
				'client' => __( 'Author', 'magenta' ),
				'meta'   => __( 'Book &middot; Full-bleed photography', 'magenta' ),
				'alt'    => __( 'The same book opened flat beside a canvas print of a palm against a blue sky.', 'magenta' ),
			),
			array(
				'slug'   => 'wave-canvas',
				'title'  => __( 'Breaking wave', 'magenta' ),
				'client' => __( 'Fine art', 'magenta' ),
				'meta'   => __( 'Canvas &middot; Printed &amp; stretched', 'magenta' ),
				'alt'    => __( 'A large canvas of a breaking wave laid out flat after printing.', 'magenta' ),
			),
			array(
				'slug'   => 'palm-print',
				'title'  => __( 'Palms, monochrome', 'magenta' ),
				'client' => __( 'Photography', 'magenta' ),
				'meta'   => __( 'Archival print &middot; Fine art paper', 'magenta' ),
				'alt'    => __( 'A black and white photographic print of palm trees along a waterfront.', 'magenta' ),
			),
			array(
				'slug'   => 'property-brochures',
				'title'  => __( 'Property portfolio', 'magenta' ),
				'client' => __( 'Real estate', 'magenta' ),
				'meta'   => __( 'Brochures &middot; Short run', 'magenta' ),
				'alt'    => __( 'A fanned stack of property brochures showing interiors and a pool terrace.', 'magenta' ),
			),
			array(
				'slug'   => 'showroom-flyer',
				'title'  => __( 'Showroom flyer', 'magenta' ),
				'client' => __( 'Retail', 'magenta' ),
				'meta'   => __( 'Flyers &middot; QR code', 'magenta' ),
				'alt'    => __( 'A yellow and white promotional flyer with a QR code, resting on printed material.', 'magenta' ),
			),
			array(
				'slug'   => 'greeting-cards',
				'video'  => true,
				'title'  => __( 'Greeting cards', 'magenta' ),
				'client' => __( 'Artist collaboration', 'magenta' ),
				'meta'   => __( 'Cards &middot; Artwork reproduction', 'magenta' ),
				'alt'    => __( 'Illustrated greeting cards being fanned out and handled.', 'magenta' ),
			),
			array(
				'slug'   => 'photo-prints',
				'video'  => true,
				'title'  => __( 'Photography prints', 'magenta' ),
				'client' => __( 'Photographer', 'magenta' ),
				'meta'   => __( 'Archival prints &middot; Colour managed', 'magenta' ),
				'alt'    => __( 'Photographic prints coming off the printer and being checked.', 'magenta' ),
			),
			array(
				'slug'   => 'canvas-stretching',
				'video'  => true,
				'title'  => __( 'Canvas stretching', 'magenta' ),
				'client' => __( 'Fine art', 'magenta' ),
				'meta'   => __( 'Canvas &middot; Stretched by hand', 'magenta' ),
				'alt'    => __( 'A printed canvas being stretched over its frame by hand.', 'magenta' ),
			),
			array(
				'slug'   => 'note-cards',
				'video'  => true,
				'title'  => __( 'Note cards', 'magenta' ),
				'client' => __( 'Stationery', 'magenta' ),
				'meta'   => __( 'Note cards &middot; On the press', 'magenta' ),
				'alt'    => __( 'Note cards being printed and collected in a stack.', 'magenta' ),
			),
		)
	);
}

/**
 * The primary navigation, or the placeholder standing in for it.
 *
 * Shared by the header bar and the mobile drawer. The placeholder used to be
 * written inline in header.php for the desktop bar only, so a site with no
 * menu assigned in wp-admin rendered four links on desktop and nothing at all
 * in the drawer - the mobile menu looked broken because it was empty except
 * for the button underneath it.
 *
 * @param string $class Class for the generated <ul>.
 */
function magenta_primary_nav( string $class ): void {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => $class,
				'depth'          => 1,
			)
		);
		return;
	}

	// Placeholder navigation until the menu is built in wp-admin. The targets
	// are the section ids front-page.php actually renders.
	$fallback = array(
		'#work'     => __( 'Work', 'magenta' ),
		'#services' => __( 'Services', 'magenta' ),
		'#process'  => __( 'Process', 'magenta' ),
		'#studio'   => __( 'Studio', 'magenta' ),
	);

	printf( '<ul class="%s">', esc_attr( $class ) );
	foreach ( $fallback as $href => $label ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $href ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Render an image slot, or the print placeholder standing in for it.
 *
 * The placeholder is not a broken-image state. It is a designed element that
 * carries the slot name and, on hover, the shooting spec - so the layout holds
 * its shape and anyone looking at the page can see what is still outstanding.
 *
 * @param string $slot Slot key from magenta_slots().
 * @param array  $args {
 *     @type bool   $eager Paint immediately - use for above-the-fold only.
 *     @type string $class Extra classes on the rendered element.
 *     @type string $sizes Explicit sizes attribute.
 * }
 */
function magenta_slot_image( string $slot, array $args = array() ): void {
	$defaults = array(
		'eager' => false,
		'class' => '',
		'sizes' => '',
	);
	$a = wp_parse_args( $args, $defaults );

	$registry = magenta_slots();
	if ( ! isset( $registry[ $slot ] ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			printf( '<!-- magenta: unknown slot "%s" -->', esc_html( $slot ) );
		}
		return;
	}

	$def = $registry[ $slot ];
	$id  = magenta_slot_id( $slot );

	if ( $id ) {
		$attr = array(
			'class'         => trim( 'slot-img ' . $a['class'] ),
			'loading'       => $a['eager'] ? 'eager' : 'lazy',
			'fetchpriority' => $a['eager'] ? 'high' : 'auto',
			'decoding'      => $a['eager'] ? 'sync' : 'async',
		);
		if ( $a['sizes'] ) {
			$attr['sizes'] = $a['sizes'];
		}
		echo wp_get_attachment_image( $id, $def['size'], false, $attr );
		return;
	}

	/*
	 * A photograph shipped with the theme. Real work, so it carries real alt
	 * text rather than the empty alt a decorative graphic gets.
	 */
	if ( ! empty( $def['photo']['file'] ) ) {
		printf(
			'<img src="%1$s" alt="%2$s" class="slot-img %3$s" width="%4$d" height="%5$d" loading="%6$s" decoding="%7$s"%8$s>',
			esc_url( MAGENTA_URI . '/assets/img/photos/' . $def['photo']['file'] ),
			esc_attr( $def['photo']['alt'] ),
			esc_attr( $a['class'] ),
			'16x9' === $def['ratio'] ? 1600 : 900,
			'16x9' === $def['ratio'] ? 900 : 900,
			$a['eager'] ? 'eager' : 'lazy',
			$a['eager'] ? 'sync' : 'async',
			$a['eager'] ? ' fetchpriority="high"' : ''
		);
		return;
	}

	/*
	 * No photograph uploaded yet, but the slot ships with brand artwork.
	 *
	 * These are drawn in the same CMYK process language as the rest of the
	 * site - halftone screens on their real angles, registration marks, colour
	 * bars. They are deliberately non-representational: a generated graphic
	 * must never be mistaken for a photograph of work the studio has produced,
	 * so none of them depicts a printed job. They make the page look finished
	 * while the real shoot is outstanding, and step aside the moment a photo is
	 * uploaded to the slot.
	 *
	 * The value is a path under assets/img/, so a slot can point either at a
	 * generated stand-in in graphics/ or at real brand artwork in brand/.
	 */
	if ( ! empty( $def['graphic'] ) ) {
		// Intrinsic size comes from the slot's own ratio, so the box is
		// reserved at the right shape and the artwork cannot shift the layout.
		$ratios = array(
			'4x5'  => array( 1000, 1250 ),
			'16x9' => array( 1600, 900 ),
			'1x1'  => array( 900, 900 ),
			'cut'  => array( 1200, 900 ),
		);
		$dim = isset( $ratios[ $def['ratio'] ] ) ? $ratios[ $def['ratio'] ] : array( 1200, 1200 );

		printf(
			'<img src="%1$s" alt="" role="presentation" class="slot-img slot-img--graphic %2$s" width="%3$d" height="%4$d" loading="%5$s" decoding="%6$s"%7$s>',
			esc_url( MAGENTA_URI . '/assets/img/' . $def['graphic'] ),
			esc_attr( $a['class'] ),
			$dim[0],
			$dim[1],
			$a['eager'] ? 'eager' : 'lazy',
			$a['eager'] ? 'sync' : 'async',
			$a['eager'] ? ' fetchpriority="high"' : ''
		);
		return;
	}

	printf(
		'<div class="slot-ph slot-ph--%1$s %2$s" role="img" aria-label="%3$s" title="%4$s">
			<span class="slot-ph__mark" aria-hidden="true"></span>
			<span class="slot-ph__id">%5$s</span>
			<span class="slot-ph__spec">%4$s</span>
		</div>',
		esc_attr( $def['ratio'] ),
		esc_attr( $a['class'] ),
		/* translators: %s: name of the photograph that has not been shot yet. */
		esc_attr( sprintf( __( 'Photograph pending: %s', 'magenta' ), $def['label'] ) ),
		esc_attr( $def['spec'] ),
		esc_html( strtoupper( str_replace( '_', ' ', $slot ) ) )
	);
}

/**
 * Render a theme-bundled responsive image from assets/img/work/.
 *
 * These are the client's own supplied photographs, shipped with the theme
 * rather than uploaded, so the site shows real work from the first minute it
 * is live. Anything published later as a `project` post takes precedence and
 * uses the media library in the normal way.
 *
 * Widths are generated by tools/optimize-media.ps1 and must stay in sync with
 * the ladder there.
 *
 * @param string $slug  Base filename, without width suffix or extension.
 * @param string $alt   Alternative text. Required - these carry meaning.
 * @param array  $args {
 *     @type string $sizes Sizes attribute.
 *     @type string $class Extra classes.
 *     @type bool   $eager Paint immediately.
 * }
 */
function magenta_asset_image( string $slug, string $alt, array $args = array() ): void {
	$a = wp_parse_args(
		$args,
		array(
			'sizes' => '(max-width: 700px) 90vw, 30vw',
			'class' => '',
			'eager' => false,
		)
	);

	$widths = array( 480, 900 );
	$base   = MAGENTA_URI . '/assets/img/work/' . $slug;

	$srcset = array();
	foreach ( $widths as $w ) {
		$srcset[] = sprintf( '%s-%d.webp %dw', $base, $w, $w );
	}

	printf(
		'<img src="%1$s" srcset="%2$s" sizes="%3$s" width="900" height="1125" alt="%4$s" class="slot-img %5$s" loading="%6$s" decoding="%7$s"%8$s>',
		esc_url( $base . '-900.webp' ),
		esc_attr( implode( ', ', $srcset ) ),
		esc_attr( $a['sizes'] ),
		esc_attr( $alt ),
		esc_attr( $a['class'] ),
		$a['eager'] ? 'eager' : 'lazy',
		$a['eager'] ? 'sync' : 'async',
		$a['eager'] ? ' fetchpriority="high"' : ''
	);
}

/**
 * Render a bundled, muted, looping reel from assets/video/.
 *
 * Decorative motion: no audio track exists in the encoded files at all, the
 * poster carries the first legible frame, and playback is skipped entirely for
 * anyone who has asked for reduced motion.
 *
 * @param string $slug Base filename without extension.
 * @param string $alt  Description of what the clip shows.
 */
function magenta_reel( string $slug, string $alt ): void {
	$base = MAGENTA_URI . '/assets/video/' . $slug;
	$dir  = MAGENTA_DIR . '/assets/video/';

	printf(
		'<video class="reel__video" poster="%s" muted playsinline loop preload="none" aria-label="%s" data-reel>',
		esc_url( $base . '-poster.webp' ),
		esc_attr( $alt )
	);

	// VP9 only ships where it actually beat H.264 during encoding.
	if ( file_exists( $dir . $slug . '.webm' ) ) {
		printf( '<source src="%s" type="video/webm">', esc_url( $base . '.webm' ) );
	}
	printf( '<source src="%s" type="video/mp4">', esc_url( $base . '.mp4' ) );

	echo '</video>';
}



/**
 * The brand rule.
 *
 * Three flat bands in the studio's own colours, used wherever the press
 * colour bar used to run. That bar was a CMYK control strip - a device from
 * the print-shop treatment this theme has moved away from, and one that
 * implied a four-plate process the studio does not run. This says the same
 * thing structurally, in the identity's own palette.
 *
 * Decorative: never announced to assistive technology.
 *
 * @param string $class Extra classes for placement.
 */
function magenta_brand_rule( string $class = '' ): void {
	printf( '<div class="brand-rule %s" aria-hidden="true">', esc_attr( $class ) );
	foreach ( array( 'm', 'teal', 'y' ) as $band ) {
		printf( '<span class="brand-rule__band brand-rule__band--%s"></span>', esc_attr( $band ) );
	}
	echo '</div>';
}

/**
 * A looping ticker. Content is duplicated once so the loop has no seam; the
 * duplicate is hidden from assistive technology.
 *
 * @param string[] $items Strings to repeat.
 */
function magenta_marquee( array $items, string $class = '' ): void {
	printf( '<div class="marquee %s" data-marquee>', esc_attr( $class ) );
	for ( $pass = 0; $pass < 2; $pass++ ) {
		printf( '<div class="marquee__track"%s>', 0 === $pass ? '' : ' aria-hidden="true"' );
		foreach ( $items as $item ) {
			printf(
				'<span class="marquee__item">%s<span class="marquee__sep" aria-hidden="true">&#10033;</span></span>',
				esc_html( $item )
			);
		}
		echo '</div>';
	}
	echo '</div>';
}

/**
 * Escaped, translated string wrapped in a highlighter sweep.
 */
function magenta_highlight( string $text, string $colour = 'y' ): string {
	return sprintf(
		'<mark class="hl hl--%s">%s</mark>',
		esc_attr( $colour ),
		esc_html( $text )
	);
}
