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
