<?php
/**
 * Render preview.html from the real templates.
 *
 * preview.html exists so the design can be reviewed without a PHP environment,
 * and its own header has always claimed it "cannot drift from the live theme".
 * That was only true of the CSS, which it links by relative path - the markup
 * was duplicated by hand, so every copy change had to be mirrored twice and the
 * two versions drifted apart constantly.
 *
 * This renders the actual header, front-page partials and footer through a
 * WordPress stub, so the preview is generated from the same source the site
 * runs. Regenerate after touching any template:
 *
 *   php tools/build-preview.php
 *
 * The stubs below are deliberately dumb. They exist to let templates execute,
 * not to emulate WordPress: anything that would hit the database returns an
 * empty result, which is exactly the state a fresh install is in.
 *
 * @package Magenta
 */

define( 'ABSPATH', true );
define( 'MAGENTA_PREVIEW', true );

$root = dirname( __DIR__ );

define( 'MAGENTA_DIR', $root );
define( 'MAGENTA_URI', '.' );
define( 'MAGENTA_VERSION', 'preview' );
define( 'MAGENTA_AGENCY_NAME', 'Toc Toc Marketing' );
define( 'MAGENTA_AGENCY_URL', 'https://toctoc.ky/' );
define( 'MAGENTA_AGENCY_BADGE_LINK', 'https://toctoc.ky/seo-checker/' );
define( 'MAGENTA_AGENCY_BADGE_SRC', 'https://toctoc.ky/wp-admin/admin-ajax.php?action=toctoc_seo_badge&v=2&seo=100&geo=86&t=7bf937fc1c' );

/* ------------------------------------------------------- escaping & i18n */
function __( $t, $d = null ) { return $t; }
function _e( $t, $d = null ) { echo $t; }
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_html_e( $t, $d = null ) { echo $t; }
function esc_attr_e( $t, $d = null ) { echo esc_attr( $t ); }
function esc_html__( $t, $d = null ) { return $t; }
function esc_attr__( $t, $d = null ) { return $t; }
function esc_url( $u ) { return (string) $u; }
function esc_url_raw( $u ) { return (string) $u; }
function esc_textarea( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function wp_kses_post( $s ) { return $s; }
function wp_strip_all_tags( $s ) { return strip_tags( (string) $s ); }
function sanitize_text_field( $v ) { return $v; }
function sanitize_textarea_field( $v ) { return $v; }
function sanitize_email( $v ) { return $v; }
function wp_specialchars_decode( $s ) { return html_entity_decode( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function wp_trim_words( $s, $n = 55, $m = '' ) { return $s; }
function number_format_i18n( $n ) { return number_format( $n ); }
function wp_parse_args( $args, $defaults = array() ) {
	if ( is_object( $args ) ) { $args = get_object_vars( $args ); }
	return array_merge( $defaults, (array) $args );
}
function wp_list_pluck( $list, $field ) {
	return array_map( function ( $row ) use ( $field ) {
		return is_array( $row ) ? ( $row[ $field ] ?? '' ) : ( $row->$field ?? '' );
	}, (array) $list );
}
function absint( $n ) { return abs( (int) $n ); }
function checked( $a, $b = true, $echo = true ) {}
function selected( $a, $b = true, $echo = true ) {}
function submit_button() {}
function settings_fields( $g ) {}

/* A WP_Query that always comes back empty, which is what a fresh install
   returns before any project posts exist - so the templates take their
   bundled-fallback path, exactly as they do on a new site. */
class WP_Query {
	public $posts = array();
	public $found_posts = 0;
	public function __construct( $args = array() ) {}
	public function have_posts() { return false; }
	public function the_post() {}
	public function rewind_posts() {}
}
class WP_Post {}
function wp_reset_postdata() {}
function the_title() {}
function get_the_title() { return ''; }
function get_permalink() { return '#'; }
function the_permalink() { echo '#'; }
function get_the_ID() { return 0; }
function get_the_date() { return ''; }
function get_the_modified_date() { return ''; }
function get_post_type_archive_link( $t ) { return '#work'; }
function get_term_link( $t ) { return '#'; }
function single_term_title( $p = '', $e = false ) { return ''; }

/* ------------------------------------------------------------ hooks/opts */
function add_action() {}
function add_filter() {}
function remove_action() {}
function do_action() {}
function apply_filters( $hook, $value ) { return $value; }
function get_option( $k, $d = array() ) { return $d; }
function get_theme_mod( $k, $d = false ) { return $d; }
function register_setting() {}
function add_theme_page() {}
function add_image_size() {}
function wp_enqueue_media() {}
function wp_enqueue_script() {}
function wp_enqueue_style() {}
function wp_add_inline_script() {}
function wp_localize_script() {}
function add_theme_support() {}
function register_nav_menus() {}
function load_theme_textdomain() {}
function wp_get_theme() { return new class { public function get( $k ) { return 'preview'; } }; }

/* --------------------------------------------------------- query context */
function is_front_page() { return true; }
function is_home() { return false; }
function is_singular( $t = '' ) { return false; }
function is_tax( $t = '' ) { return false; }
function is_category() { return false; }
function is_tag() { return false; }
function is_post_type_archive( $t = '' ) { return false; }
function have_posts() { return false; }
function current_user_can( $c ) { return false; }
function get_queried_object() { return null; }
function get_query_var( $v ) { return ''; }

/* -------------------------------------------------------------- content */
function get_posts( $a = array() ) { return array(); }
function get_pages( $a = array() ) { return array(); }
function get_terms( $a = array() ) { return array(); }
function is_wp_error( $t ) { return false; }
function has_nav_menu( $l ) { return false; }
function wp_nav_menu( $a ) {}
function has_post_thumbnail() { return false; }
function get_post_thumbnail_id() { return 0; }
function wp_get_attachment_image() { return ''; }
function wp_get_attachment_image_src() { return false; }
function get_post_meta() { return ''; }
function has_excerpt( $p = null ) { return false; }
function get_the_excerpt( $p = null ) { return ''; }
function term_description() { return ''; }
function magenta_project_client( $id = 0 ) { return ''; }

/* ------------------------------------------------------------- site info */
function home_url( $p = '/' ) { return $p === '/' ? '#top' : $p; }
function trailingslashit( $s ) { return rtrim( (string) $s, '/' ) . '/'; }
function get_bloginfo( $k = '' ) {
	$map = array(
		'name'        => 'Magenta',
		'description' => 'Design, print, framing and fine art reproduction in Grand Cayman.',
		'charset'     => 'UTF-8',
	);
	return isset( $map[ $k ] ) ? $map[ $k ] : '';
}
function bloginfo( $k = '' ) { echo get_bloginfo( $k ); }
function language_attributes() { echo 'lang="en"'; }
function body_class( $c = '' ) { echo 'class="home preview"'; }
function wp_body_open() {}
function wp_head() {}
function wp_footer() {}
function get_search_form() {}

/* ------------------------------------------------------------ templating */
function get_template_directory() { return MAGENTA_DIR; }
function get_template_directory_uri() { return '.'; }

function get_template_part( $slug, $name = null, $args = array() ) {
	$file = MAGENTA_DIR . '/' . $slug . ( $name ? "-{$name}" : '' ) . '.php';
	if ( file_exists( $file ) ) {
		include $file;
	}
}

/* ------------------------------------------------------------------ boot */
require_once MAGENTA_DIR . '/inc/media-slots.php';
require_once MAGENTA_DIR . '/inc/helpers.php';
require_once MAGENTA_DIR . '/inc/doodles.php';
require_once MAGENTA_DIR . '/inc/business.php';
require_once MAGENTA_DIR . '/inc/faq.php';

/* --------------------------------------------------------------- render */
/*
 * front-page.php is included rather than reimplemented. It listed its own
 * section order here once, which is exactly the duplication this generator
 * exists to remove - a section dropped from the theme stayed in the preview.
 */
function get_header() { include MAGENTA_DIR . '/header.php'; }
function get_footer() { include MAGENTA_DIR . '/footer.php'; }

ob_start();
include MAGENTA_DIR . '/front-page.php';
$html = ob_get_clean();

/*
 * The templates emit a bare <head> because wp_head() is stubbed out. Splice in
 * what WordPress would have contributed: the stylesheets, the webfonts and the
 * favicon, all by relative path so the file opens straight off disk.
 */
$head = <<<HTML

<!--
  Static preview of front-page.php.

  GENERATED FILE - do not edit by hand. Rendered from the real templates by
  tools/build-preview.php; run that again after changing any template. It was
  previously maintained as a hand-copied duplicate, which drifted from the
  theme on almost every change.
-->

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo:wght@400;700&family=Caveat&family=Space+Mono&display=swap" rel="stylesheet">

<link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
<link rel="icon" href="assets/img/favicon-32.png" sizes="32x32" type="image/png">
<link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">

<link rel="stylesheet" href="assets/css/tokens.css">
<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/components.css">
<link rel="stylesheet" href="assets/css/home.css">
<title>Magenta &mdash; homepage preview</title>
</head>
HTML;

$html = str_replace( '</head>', $head, $html );
$html = str_replace( '</body>', "<script src=\"assets/js/main.js\" defer></script>\n</body>", $html );

file_put_contents( MAGENTA_DIR . '/preview.html', $html );

printf( "preview.html rendered from templates: %s bytes\n", number_format( strlen( $html ) ) );
