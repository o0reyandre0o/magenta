<?php
/**
 * Hand-drawn layer.
 *
 * The rest of the theme speaks in press language - registration marks, colour
 * bars, halftone, tape. That vocabulary is precise by nature, and precision
 * alone reads cold. These are the marks a designer makes *on top of* a proof:
 * a word lassoed in marker, an arrow pointing at the thing that matters, a
 * squiggle in the margin. They are what stops the page looking machined.
 *
 * Every doodle is inline SVG - no requests, no raster, scales to any size, and
 * takes its colour from `currentColor` so one shape serves every section.
 *
 * The paths are drawn deliberately irregular: loops overshoot where they
 * close, strokes vary, nothing is concentric. A geometrically perfect circle
 * reads as a border, not as a hand.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The mark set.
 *
 * @return array<string, array{box:string, paths:array<int, array{d:string, w:float}>}>
 */
function magenta_doodle_set(): array {
	return array(

		// A loop thrown around a word, closing past where it started.
		'lasso' => array(
			'box'   => '0 0 240 92',
			'paths' => array(
				array(
					'd' => 'M64 16C36 20 12 34 10 50c-2 18 30 30 78 32 48 2 108-8 138-26 26-16 6-32-34-38-42-6-88 0-114 12-22 10-16 26 6 32',
					'w' => 4.5,
				),
			),
		),

		// Marker underline: two passes, because one never lands flat.
		'underline' => array(
			'box'   => '0 0 230 26',
			'paths' => array(
				array( 'd' => 'M8 15c48-8 110-11 176-5', 'w' => 7 ),
				array( 'd' => 'M18 21c44-6 102-8 158-3', 'w' => 3.5 ),
			),
		),

		/*
		 * Arrowheads are drawn long relative to the shaft. These render at
		 * under 100px on the page, where a head of 20 viewBox units against a
		 * 240-unit shaft disappears into a speck. Stroke weights are up for
		 * the same reason: 4 units in a 130-unit box scaled to 90px paints a
		 * 2.7px line, which reads as a hairline rather than as marker.
		 */
		'arrow' => array(
			'box'   => '0 0 130 112',
			'paths' => array(
				array( 'd' => 'M12 10c8 34 30 62 60 78 12 6 26 11 40 13', 'w' => 5.5 ),
				array( 'd' => 'M112 101l-9-27', 'w' => 5.5 ),
				array( 'd' => 'M112 101l-28 6', 'w' => 5.5 ),
			),
		),

		// Arrow that curls back on itself before setting off.
		'arrow-loop' => array(
			'box'   => '0 0 144 122',
			'paths' => array(
				array(
					'd' => 'M10 16c18-9 35 1 33 18-2 16-25 19-29 5-4-16 19-31 44-25 27 6 48 35 54 68',
					'w' => 5.5,
				),
				array( 'd' => 'M112 102l-6-29', 'w' => 5.5 ),
				array( 'd' => 'M112 102l-28-5', 'w' => 5.5 ),
			),
		),

		// Margin scribble.
		'squiggle' => array(
			'box'   => '0 0 124 74',
			'paths' => array(
				array(
					'd' => 'M8 48c4-21 21-35 35-29 15 6 11 31-6 33-17 2-23-21-6-33 17-13 46-6 61 13',
					'w' => 4,
				),
			),
		),

		// Six-arm asterisk, arms not quite even.
		'asterisk' => array(
			'box'   => '0 0 46 46',
			'paths' => array(
				array( 'd' => 'M23 4v38', 'w' => 4 ),
				array( 'd' => 'M7 13l32 20', 'w' => 4 ),
				array( 'd' => 'M39 13L7 33', 'w' => 4 ),
			),
		),

		// Four-point sparkle, drawn as a stroke so it animates with the rest.
		'sparkle' => array(
			'box'   => '0 0 44 44',
			'paths' => array(
				array( 'd' => 'M22 3c2 13 6 19 19 19-13 2-17 6-19 19-2-13-6-17-19-19 13 0 17-6 19-19', 'w' => 3.5 ),
			),
		),

		// Energy line.
		'zigzag' => array(
			'box'   => '0 0 146 32',
			'paths' => array(
				array( 'd' => 'M6 24l23-17 22 17 23-17 22 17 23-17 21 16', 'w' => 4 ),
			),
		),
	);
}

/**
 * Print a doodle.
 *
 * @param string $name Key from magenta_doodle_set().
 * @param array  $args {
 *     @type string $class  Extra classes for placement.
 *     @type string $colour Token suffix: m, c, y, k, paper.
 *     @type bool   $draw   Animate the stroke on entry.
 * }
 */
function magenta_doodle( string $name, array $args = array() ): void {
	$set = magenta_doodle_set();

	if ( ! isset( $set[ $name ] ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			printf( '<!-- magenta: unknown doodle "%s" -->', esc_html( $name ) );
		}
		return;
	}

	$a = wp_parse_args(
		$args,
		array(
			'class'  => '',
			'colour' => 'm',
			'draw'   => true,
		)
	);

	$doodle = $set[ $name ];

	printf(
		'<svg class="doodle doodle--%1$s doodle--ink-%2$s %3$s%4$s" viewBox="%5$s" fill="none" aria-hidden="true" focusable="false">',
		esc_attr( $name ),
		esc_attr( $a['colour'] ),
		esc_attr( $a['class'] ),
		$a['draw'] ? ' doodle--draw' : '',
		esc_attr( $doodle['box'] )
	);

	foreach ( $doodle['paths'] as $path ) {
		printf(
			'<path d="%s" stroke="currentColor" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round"/>',
			esc_attr( $path['d'] ),
			esc_attr( (string) $path['w'] )
		);
	}

	echo '</svg>';
}

/**
 * Wrap a run of text in a mark - a lasso thrown round it, or a marker
 * underline swept beneath it.
 *
 * Returns markup rather than printing so it can be dropped into a translated
 * string alongside other content.
 *
 * @param string $text   Text to mark. Escaped here.
 * @param string $doodle 'lasso' or 'underline'.
 * @param string $colour Token suffix.
 */
function magenta_mark( string $text, string $doodle = 'lasso', string $colour = 'y' ): string {
	ob_start();
	magenta_doodle(
		$doodle,
		array(
			'colour' => $colour,
			'class'  => 'doodle--mark doodle--mark-' . $doodle,
		)
	);
	$svg = ob_get_clean();

	return sprintf(
		'<span class="marked">%s%s</span>',
		esc_html( $text ),
		$svg
	);
}

/**
 * The brand blob.
 *
 * Every icon and sticker in the brand kit sits on the same hand-drawn organic
 * shape - it is the identity's core device, the way registration marks were
 * the device of the treatment this replaced. Lifted verbatim from the submark
 * artwork so it is the real curve, not an approximation of it.
 *
 * Decorative only: it is never in the accessibility tree, and it takes its
 * colour from currentColor so a placement class can tint it.
 *
 * @param string $class Placement class.
 * @param string $flip  '', 'h', 'v' or 'hv' to mirror it, so repeated blobs do
 *                      not read as the same stamp copied around the page.
 */
function magenta_blob( string $class = '', string $flip = '' ): void {
	$sx = false !== strpos( $flip, 'h' ) ? -1 : 1;
	$sy = false !== strpos( $flip, 'v' ) ? -1 : 1;

	printf(
		'<svg class="blob %1$s" viewBox="0 0 500 500" fill="currentColor" aria-hidden="true" focusable="false" preserveAspectRatio="xMidYMid meet">
			<g transform="translate(%2$d %3$d) scale(%4$d %5$d)"><path d="%6$s"/></g>
		</svg>',
		esc_attr( $class ),
		1 === $sx ? 0 : 500,
		1 === $sy ? 0 : 500,
		$sx,
		$sy,
		'M270.25,38.89c-85.23-4.01-188.77,43.24-219.69,127.05-13.6,37.93-10.94,79.98-5.18,119.45,9.32,56.32,24.82,121.59,77.3,152.81,33.03,19.41,73.13,23.18,110.83,24.63,67.45,2.02,143.67-10.08,192.05-60.98,30.04-31.42,36.71-76.48,33.45-118.54-1.68-25.63-5.27-51.22-9.78-76.52-6.1-33.41-15.06-66.84-34.37-94.94-31.69-47.31-88.85-70.89-144.48-72.96h-.13Z'
	);
}
