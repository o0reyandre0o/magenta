<?php
/**
 * Asset loading.
 *
 * No build step by design: the theme is synced straight from git to
 * wp-content/themes, so what is committed has to be what runs.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Self-hosted webfonts we expect in /assets/fonts/.
 *
 * Until the files land, magenta_has_local_fonts() returns false and the stack
 * in tokens.css falls back to close system equivalents, so the layout never
 * breaks - same principle as the image slots.
 */
const MAGENTA_LOCAL_FONT_FILES = array(
	'anton-v25-latin-regular.woff2',
	'archivo-v19-latin-regular.woff2',
	'archivo-v19-latin-700.woff2',
	'space-mono-v13-latin-regular.woff2',
	'caveat-v18-latin-regular.woff2',
);

function magenta_has_local_fonts(): bool {
	static $has = null;
	if ( null !== $has ) {
		return $has;
	}
	$has = true;
	foreach ( MAGENTA_LOCAL_FONT_FILES as $file ) {
		if ( ! file_exists( MAGENTA_DIR . '/assets/fonts/' . $file ) ) {
			$has = false;
			break;
		}
	}
	return $has;
}

function magenta_enqueue(): void {
	$v   = MAGENTA_VERSION;
	$uri = MAGENTA_URI . '/assets/css/';

	wp_enqueue_style( 'magenta-tokens', $uri . 'tokens.css', array(), $v );
	wp_enqueue_style( 'magenta-base', $uri . 'base.css', array( 'magenta-tokens' ), $v );
	wp_enqueue_style( 'magenta-components', $uri . 'components.css', array( 'magenta-base' ), $v );

	if ( is_front_page() ) {
		wp_enqueue_style( 'magenta-home', $uri . 'home.css', array( 'magenta-components' ), $v );
	}

	if ( magenta_has_local_fonts() ) {
		wp_enqueue_style( 'magenta-fonts', $uri . 'fonts.css', array(), $v );
	} else {
		// Interim: Google Fonts so the design reads correctly during build.
		// Remove this branch once the woff2 files are committed - see README.
		wp_enqueue_style(
			'magenta-fonts-remote',
			'https://fonts.googleapis.com/css2?family=Anton&family=Archivo:wght@400;700&family=Caveat&family=Space+Mono&display=swap',
			array(),
			null
		);
	}

	wp_enqueue_script( 'magenta-main', MAGENTA_URI . '/assets/js/main.js', array(), $v, true );

	wp_localize_script(
		'magenta-main',
		'magentaData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'action'  => MAGENTA_CONTACT_ACTION,
			'nonce'   => wp_create_nonce( MAGENTA_CONTACT_ACTION ),
			'i18n'    => array(
				'sending' => __( 'Sending…', 'magenta' ),
				'error'   => __( 'That did not go through. Try again in a moment.', 'magenta' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'magenta_enqueue' );

/**
 * Preload the two faces that paint above the fold.
 *
 * Uses the wp_preload_resources filter rather than hooking wp_head: by the
 * time wp_enqueue_scripts runs, wp_head is already past the early priorities,
 * so a link added from there would never be printed.
 */
function magenta_preload_fonts( array $preload ): array {
	if ( ! magenta_has_local_fonts() ) {
		return $preload;
	}

	foreach ( array( 'anton-v25-latin-regular.woff2', 'archivo-v19-latin-regular.woff2' ) as $file ) {
		$preload[] = array(
			'href'        => MAGENTA_URI . '/assets/fonts/' . $file,
			'as'          => 'font',
			'type'        => 'font/woff2',
			'crossorigin' => 'anonymous',
		);
	}

	return $preload;
}
add_filter( 'wp_preload_resources', 'magenta_preload_fonts' );

/**
 * Preconnect only while the remote font fallback is in play.
 */
function magenta_resource_hints( array $hints, string $relation ): array {
	if ( 'preconnect' === $relation && ! magenta_has_local_fonts() ) {
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'magenta_resource_hints', 10, 2 );
