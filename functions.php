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
 * The audited SEO / GEO score badge shown in the footer.
 *
 * The scores are baked into the image URL as query parameters rather than
 * being read live, so this is a snapshot: re-run the checker and the src has
 * to be updated here for the badge to show the new numbers. Kept beside the
 * credit constants so there is exactly one place to change it.
 */
define( 'MAGENTA_AGENCY_BADGE_LINK', 'https://toctoc.ky/seo-checker/' );
define( 'MAGENTA_AGENCY_BADGE_SRC', 'https://toctoc.ky/wp-admin/admin-ajax.php?action=toctoc_seo_badge&v=2&seo=100&geo=86&t=7bf937fc1c' );

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
