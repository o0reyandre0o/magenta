<?php
/**
 * Magenta theme bootstrap.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MAGENTA_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'MAGENTA_DIR', get_template_directory() );
define( 'MAGENTA_URI', get_template_directory_uri() );

/**
 * Agency credit. Used by the footer and by the schema / llms.txt output.
 * Keep these in one place so the credit can never drift between surfaces.
 */
define( 'MAGENTA_AGENCY_NAME', 'Toc Toc Marketing' );
define( 'MAGENTA_AGENCY_URL', 'https://toctoc.ky/' );

require_once MAGENTA_DIR . '/inc/setup.php';
require_once MAGENTA_DIR . '/inc/enqueue.php';
require_once MAGENTA_DIR . '/inc/media-slots.php';
require_once MAGENTA_DIR . '/inc/helpers.php';
require_once MAGENTA_DIR . '/inc/doodles.php';
require_once MAGENTA_DIR . '/inc/cpt-project.php';
require_once MAGENTA_DIR . '/inc/contact.php';
require_once MAGENTA_DIR . '/inc/schema.php';
require_once MAGENTA_DIR . '/inc/meta.php';
require_once MAGENTA_DIR . '/inc/llms-txt.php';
