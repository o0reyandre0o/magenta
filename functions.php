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

/*
 * The studio's Google Maps embed, taken from the verified Google Business
 * listing. Regenerate from Google Maps > Share > Embed a map if the listing
 * ever moves; the place id in it is what pins the map to the real business.
 */
define( 'MAGENTA_MAP_EMBED', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3765.583673844109!2d-81.36838431386226!3d19.30046287962633!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f2587c4554eac5b%3A0x7aeeb46fc80e6163!2sMagenta%20Creative%20Studio!5e0!3m2!1sen!2sus!4v1788363126993!5m2!1sen!2sus' );


require_once MAGENTA_DIR . '/inc/setup.php';
require_once MAGENTA_DIR . '/inc/enqueue.php';
require_once MAGENTA_DIR . '/inc/media-slots.php';
require_once MAGENTA_DIR . '/inc/helpers.php';
require_once MAGENTA_DIR . '/inc/doodles.php';
require_once MAGENTA_DIR . '/inc/cpt-project.php';
require_once MAGENTA_DIR . '/inc/business.php';
require_once MAGENTA_DIR . '/inc/faq.php';
require_once MAGENTA_DIR . '/inc/contact.php';
require_once MAGENTA_DIR . '/inc/schema.php';
require_once MAGENTA_DIR . '/inc/meta.php';
require_once MAGENTA_DIR . '/inc/llms-txt.php';
