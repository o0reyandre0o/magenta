<?php
/**
 * Theme setup: supports, image sizes, menus.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Image sizes.
 *
 * These four ratios are the contract with the photographer. They are printed
 * verbatim in the Photo Brief (Appearance > Magenta Media) so that what gets
 * shot matches what the theme crops to. Change them here and the brief updates
 * itself - never change one without the other.
 */
const MAGENTA_IMAGE_SIZES = array(
	'magenta-4x5'  => array( 1200, 1500, true ),  // Portraits, vertical cards.
	'magenta-16x9' => array( 1920, 1080, true ),  // Hero, wide banners.
	'magenta-1x1'  => array( 900, 900, true ),    // Grid tiles, sticker crops.
	'magenta-cut'  => array( 1400, 0, false ),    // Cut-outs on white, no crop.
);

function magenta_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' )
	);

	foreach ( MAGENTA_IMAGE_SIZES as $name => $args ) {
		add_image_size( $name, $args[0], $args[1], $args[2] );
	}

	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'magenta' ),
			'footer'  => __( 'Footer navigation', 'magenta' ),
		)
	);
}
add_action( 'after_setup_theme', 'magenta_setup' );

/**
 * Content width used by oEmbeds and wide images.
 */
function magenta_content_width(): void {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'magenta_content_width', 0 );

/**
 * Allow SVG uploads for the sticker/texture set, restricted to administrators.
 *
 * SVG is an executable format - anyone who can upload one can inject script
 * into the admin. Editors and authors deliberately do not get this.
 */
function magenta_allow_svg( array $mimes ): array {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'magenta_allow_svg' );

/**
 * Trim the admin bar / emoji cruft that only costs requests.
 */
function magenta_dequeue_bloat(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', 'magenta_dequeue_bloat' );
