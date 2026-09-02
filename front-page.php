<?php
/**
 * Front page.
 *
 * Each section is its own partial. Order is the argument the page makes:
 * who we are, what we make, proof, how it works, who is behind it, ask.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$sections = array( 'hero', 'ticker', 'services', 'work', 'process', 'about', 'testimonials', 'faq', 'cta' );

foreach ( $sections as $section ) {
	get_template_part( 'template-parts/home/' . $section );
}

get_footer();
