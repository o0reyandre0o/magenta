<?php
/**
 * Home / ticker.
 *
 * The list this replaced ran Offset / Large format / Screen printing /
 * Packaging / Signage - a print shop's departments, none of which the studio
 * offers. These are the real categories, and they have to stay in step with
 * template-parts/home/services.php.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

magenta_marquee(
	array(
		__( 'Graphic design', 'magenta' ),
		__( 'Wedding stationery', 'magenta' ),
		__( 'Fine art printing', 'magenta' ),
		__( 'Custom framing', 'magenta' ),
		__( 'Stickers & labels', 'magenta' ),
		__( 'Foil & specialty', 'magenta' ),
		__( 'Artwork digitization', 'magenta' ),
		__( 'Brand identity', 'magenta' ),
	),
	'marquee--band'
);
