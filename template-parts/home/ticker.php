<?php
/**
 * Home / ticker.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

magenta_marquee(
	array(
		__( 'Offset', 'magenta' ),
		__( 'Large format', 'magenta' ),
		__( 'Screen printing', 'magenta' ),
		__( 'Foil & emboss', 'magenta' ),
		__( 'Packaging', 'magenta' ),
		__( 'Signage', 'magenta' ),
		__( 'Brand identity', 'magenta' ),
	),
	'marquee--band'
);
