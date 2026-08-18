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
	 * No photograph uploaded yet, but the slot ships with brand artwork.
	 *
	 * These are drawn in the same CMYK process language as the rest of the
	 * site - halftone screens on their real angles, registration marks, colour
	 * bars. They are deliberately non-representational: a generated graphic
	 * must never be mistaken for a photograph of work the studio has produced,
	 * so none of them depicts a printed job. They make the page look finished
	 * while the real shoot is outstanding, and step aside the moment a photo is
	 * uploaded to the slot.
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
			esc_url( MAGENTA_URI . '/assets/img/graphics/' . $def['graphic'] ),
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
 * The four-colour separation headline.
 *
 * Prints the same word four times - cyan, magenta, yellow, black - stacked and
 * multiplied. JavaScript drives --reg from 1 to 0 as the block scrolls into
 * view, pulling the plates into register. Only the magenta plate is exposed to
 * assistive technology; the rest are decorative duplicates.
 *
 * @param string $text  Word or phrase to separate.
 * @param string $tag   Wrapping element.
 * @param string $class Extra classes.
 */
function magenta_cmyk_text( string $text, string $tag = 'h2', string $class = '' ): void {
	$tag   = preg_match( '/^h[1-6]$|^p$|^span$|^div$/', $tag ) ? $tag : 'h2';
	$plates = array( 'c', 'y', 'k' );

	printf( '<%1$s class="cmyk %2$s" data-cmyk>', esc_html( $tag ), esc_attr( $class ) );
	foreach ( $plates as $plate ) {
		printf(
			'<span class="cmyk__plate cmyk__plate--%1$s" aria-hidden="true">%2$s</span>',
			esc_attr( $plate ),
			esc_html( $text )
		);
	}
	printf( '<span class="cmyk__plate cmyk__plate--m">%s</span>', esc_html( $text ) );
	printf( '</%s>', esc_html( $tag ) );
}

/**
 * Registration marks, printed in the corners of a section the way they are
 * printed outside the trim on a real press sheet.
 */
function magenta_reg_marks(): void {
	echo '<div class="reg-marks" aria-hidden="true">';
	foreach ( array( 'tl', 'tr', 'bl', 'br' ) as $corner ) {
		printf(
			'<svg class="reg-mark reg-mark--%s" viewBox="0 0 40 40" width="40" height="40" fill="none">
				<circle cx="20" cy="20" r="11" stroke="currentColor" stroke-width="1.2"/>
				<path d="M20 0v40M0 20h40" stroke="currentColor" stroke-width="1.2"/>
			</svg>',
			esc_attr( $corner )
		);
	}
	echo '</div>';
}

/**
 * The colour bar that runs along the edge of a press sheet.
 */
function magenta_colour_bar( int $steps = 24 ): void {
	$ramp = array( 'var(--c)', 'var(--m)', 'var(--y)', 'var(--k)' );
	echo '<div class="colour-bar" aria-hidden="true">';
	for ( $i = 0; $i < $steps; $i++ ) {
		printf(
			'<span style="background:%s;opacity:%s"></span>',
			esc_attr( $ramp[ $i % 4 ] ),
			esc_attr( number_format( 0.35 + ( ( $i % 4 ) + 1 ) * 0.16, 2 ) )
		);
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
