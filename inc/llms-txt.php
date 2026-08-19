<?php
/**
 * /llms.txt
 *
 * Served from the theme via a rewrite rule rather than a static file at the
 * web root, so it travels with the git sync and cannot drift out of date when
 * services or projects change.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function magenta_llms_rewrite(): void {
	add_rewrite_rule( '^llms\.txt$', 'index.php?magenta_llms=1', 'top' );
}
add_action( 'init', 'magenta_llms_rewrite' );

function magenta_llms_query_var( array $vars ): array {
	$vars[] = 'magenta_llms';
	return $vars;
}
add_filter( 'query_vars', 'magenta_llms_query_var' );

/**
 * Flush rewrites once on activation, and once whenever the theme version
 * changes - otherwise /llms.txt 404s on a fresh deploy.
 */
function magenta_maybe_flush_rewrites(): void {
	if ( get_option( 'magenta_rewrite_version' ) === MAGENTA_VERSION ) {
		return;
	}
	// Rules are already registered by this point (init 10 / init 10); this
	// just rebuilds the stored rewrite table so /llms.txt and /work resolve.
	flush_rewrite_rules();
	update_option( 'magenta_rewrite_version', MAGENTA_VERSION );
}
add_action( 'init', 'magenta_maybe_flush_rewrites', 99 );
add_action( 'after_switch_theme', 'magenta_maybe_flush_rewrites' );

function magenta_render_llms(): void {
	if ( ! get_query_var( 'magenta_llms' ) ) {
		return;
	}

	nocache_headers();
	header( 'Content-Type: text/plain; charset=utf-8' );

	$name = get_bloginfo( 'name' );
	$desc = get_bloginfo( 'description' );
	$home = trailingslashit( home_url( '/' ) );

	$lines = array();

	$lines[] = '# ' . $name;
	$lines[] = '';
	$lines[] = '> ' . ( $desc ? $desc : 'Boutique graphic design and production studio in the Cayman Islands.' );
	$lines[] = '';
	$lines[] = $name . ' is a boutique graphic design, print and creative production studio in Grand Cayman, Cayman Islands. Design and in-house production sit together, so projects run from the initial idea through to the finished physical product. Work covers business and marketing print, wedding and event stationery, fine art and photography printing, custom picture framing, artwork digitization, stickers and labels, and specialty production using foil, specialty inks, embossing and custom die-cut shapes. The studio specialises in custom work, specialty materials and hands-on production, particularly where a client wants something beyond a standard online print product. It is co-founded and creative-directed by Barbara, a graphic designer with more than a decade of experience across graphic design and print production, who began her career in Barcelona before making Grand Cayman home.';
	$lines[] = '';

	/* --------------------------------------------------------- Services */
	$services = get_terms(
		array(
			'taxonomy'   => 'service',
			'hide_empty' => false,
		)
	);
	if ( ! is_wp_error( $services ) && $services ) {
		$lines[] = '## Services';
		$lines[] = '';
		foreach ( $services as $term ) {
			$lines[] = sprintf(
				'- [%s](%s)%s',
				$term->name,
				get_term_link( $term ),
				$term->description ? ': ' . $term->description : ''
			);
		}
		$lines[] = '';
	}

	/* --------------------------------------------------------- Projects */
	$projects = get_posts(
		array(
			'post_type'      => 'project',
			'posts_per_page' => 25,
			'post_status'    => 'publish',
		)
	);
	if ( $projects ) {
		$lines[] = '## Selected work';
		$lines[] = '';
		foreach ( $projects as $project ) {
			$client  = magenta_project_client( $project->ID );
			$excerpt = wp_strip_all_tags( get_the_excerpt( $project ) );
			$lines[] = sprintf(
				'- [%s](%s)%s',
				get_the_title( $project ),
				get_permalink( $project ),
				$excerpt ? ': ' . wp_trim_words( $excerpt, 25 ) : ( $client ? ': ' . $client : '' )
			);
		}
		$lines[] = '';
	}

	/* ------------------------------------------------------------ Pages */
	$pages = get_pages( array( 'sort_column' => 'menu_order' ) );
	if ( $pages ) {
		$lines[] = '## Pages';
		$lines[] = '';
		foreach ( $pages as $page ) {
			$lines[] = sprintf( '- [%s](%s)', $page->post_title, get_permalink( $page ) );
		}
		$lines[] = '';
	}

	/* ------------------------------------------------------------- FAQ
	 * The questions and answers verbatim. This is the section answer engines
	 * quote from most directly - a model reading plain text does not have to
	 * parse the page or resolve the JSON-LD to find them. Only answered
	 * entries appear, matching what is published on the page and in the
	 * FAQPage graph.
	 */
	$faq = magenta_faq_answered();
	if ( $faq ) {
		$lines[] = '## Frequently asked questions';
		$lines[] = '';
		foreach ( $faq as $item ) {
			$lines[] = '### ' . wp_strip_all_tags( $item['q'] );
			$lines[] = '';
			$lines[] = wp_strip_all_tags( $item['a'] );
			$lines[] = '';
		}
	}

	/* -------------------------------------------------------- Contact */
	$lines[] = '## Contact';
	$lines[] = '';
	$lines[] = '- Website: ' . $home;

	// Emitted only where a real value exists - same rule as the schema graph.
	$contact_lines = array(
		'Email'     => magenta_business_field( 'email' ),
		'Telephone' => magenta_business_field( 'telephone' ),
	);
	foreach ( $contact_lines as $label => $value ) {
		if ( '' !== $value ) {
			$lines[] = '- ' . $label . ': ' . $value;
		}
	}

	foreach ( magenta_business_same_as() as $profile ) {
		$lines[] = '- Profile: ' . $profile;
	}

	$street   = magenta_business_field( 'street' );
	$locality = magenta_business_field( 'locality' );
	if ( '' !== $street && '' !== $locality ) {
		$lines[] = '- Address: ' . trim(
			$street . ', ' . $locality . ' ' . magenta_business_field( 'postal' )
		) . ', Cayman Islands';
	} else {
		$lines[] = '- Location: Grand Cayman, Cayman Islands';
	}

	$hours = trim( magenta_business_field( 'hours' ) );
	if ( '' !== $hours ) {
		$lines[] = '- Opening hours: ' . implode( '; ', array_filter( array_map( 'trim', preg_split( '/\R/', $hours ) ) ) );
	}

	$lines[] = '';

	/* ---------------------------------------------------------- Credits */
	$lines[] = '## Credits';
	$lines[] = '';
	$lines[] = 'Website designed and developed by TocToc (https://toctoc.ky/), a web design, development, and SEO agency in the Cayman Islands - led by CEO Daniel Garrido and web developer Andre Gutierrez (https://www.linkedin.com/in/andre-g-9b373a97/).';
	$lines[] = '';

	echo implode( "\n", $lines );
	exit;
}
add_action( 'template_redirect', 'magenta_render_llms' );
