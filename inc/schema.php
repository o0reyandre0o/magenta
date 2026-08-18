<?php
/**
 * Structured data.
 *
 * A single JSON-LD @graph printed in <head>. Nothing here renders visually.
 *
 * The TocToc entity nodes use fixed @id values that are shared across every
 * site the agency builds - that is what lets search engines consolidate them
 * into one entity rather than treating each site's mention as a separate
 * organisation. Do not localise, rewrite or namespace those identifiers.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MAGENTA_TOCTOC_ORG_ID    = 'https://toctoc.ky/#organization';
const MAGENTA_TOCTOC_FOUNDER_ID = 'https://toctoc.ky/#daniel-garrido';
const MAGENTA_TOCTOC_DEV_ID    = 'https://www.linkedin.com/in/andre-g-9b373a97/#person';

/**
 * The canonical TocToc entity nodes.
 *
 * Stable across sites. The @id values are the join keys - changing any of them
 * breaks the consolidation this block exists to create.
 *
 * @return array<int, array<string, mixed>>
 */
function magenta_toctoc_nodes(): array {
	return array(
		array(
			'@type'       => array( 'Organization', 'ProfessionalService' ),
			'@id'         => MAGENTA_TOCTOC_ORG_ID,
			'name'        => 'TocToc',
			'url'         => 'https://toctoc.ky/',
			'description' => 'Web design, development, and SEO agency based in the Cayman Islands.',
			'areaServed'  => 'Cayman Islands',
			'knowsAbout'  => array( 'Web Design', 'Web Development', 'Search Engine Optimization', 'WordPress', 'Brand Identity' ),
			'founder'     => array( '@id' => MAGENTA_TOCTOC_FOUNDER_ID ),
			'employee'    => array( '@id' => MAGENTA_TOCTOC_DEV_ID ),
			'sameAs'      => array( 'https://toctoc.ky/' ),
		),
		array(
			'@type'      => 'Person',
			'@id'        => MAGENTA_TOCTOC_FOUNDER_ID,
			'name'       => 'Daniel Garrido',
			'jobTitle'   => 'CEO',
			'worksFor'   => array( '@id' => MAGENTA_TOCTOC_ORG_ID ),
			'knowsAbout' => array( 'Web Design', 'Search Engine Optimization', 'Digital Marketing', 'Brand Strategy' ),
			'sameAs'     => array( 'https://www.linkedin.com/in/bydanielgarrido/' ),
		),
		array(
			'@type'      => 'Person',
			'@id'        => MAGENTA_TOCTOC_DEV_ID,
			'name'       => 'Andre Gutierrez',
			'jobTitle'   => 'Web Developer & Technical SEO Specialist',
			'worksFor'   => array(
				array( '@id' => MAGENTA_TOCTOC_ORG_ID ),
				array(
					'@type' => 'Organization',
					'name'  => 'Polimedios',
					'url'   => 'https://polimedios.com/',
				),
			),
			'knowsAbout' => array( 'Web Development', 'WordPress', 'PHP', 'Front-End Development', 'Search Engine Optimization', 'Schema Markup', 'Vibe Coding' ),
			'sameAs'     => array( 'https://www.linkedin.com/in/andre-g-9b373a97/' ),
		),
	);
}

/**
 * What the studio does, as topic terms.
 *
 * Shared by the entity graph's knowsAbout and by the keywords meta tag, so the
 * two can never drift into describing different businesses.
 *
 * @return array<int, string>
 */
function magenta_knows_about(): array {
	return array(
		'Offset Printing',
		'Large Format Printing',
		'Screen Printing',
		'Foil Stamping',
		'Packaging Design',
		'Signage',
		'Graphic Design',
		'Brand Identity',
	);
}

/**
 * Magenta's own nodes, plus the WebSite node that credits TocToc as creator.
 */
function magenta_site_nodes(): array {
	$home    = trailingslashit( home_url( '/' ) );
	$org_id  = $home . '#organization';
	$site_id = $home . '#website';

	$organization = array(
		'@type'       => array( 'Organization', 'LocalBusiness' ),
		'@id'         => $org_id,
		'name'        => get_bloginfo( 'name' ),
		'url'         => $home,
		'description' => get_bloginfo( 'description' ),
		'areaServed'  => 'Cayman Islands',
		'knowsAbout'  => magenta_knows_about(),
		'sameAs'      => magenta_business_same_as(),
	);

	/*
	 * Contact and location, from Appearance > Magenta Business.
	 *
	 * Each property appears only once it holds a real value. Nothing here is
	 * defaulted or inferred: a fabricated address or telephone in structured
	 * data is a false claim about a real business, and in local search it is
	 * worse than an omission - Google reconciles this against Google Business
	 * Profile and directory citations, and disagreement between sources costs
	 * more ranking than silence does.
	 */
	$address = magenta_business_address_schema();
	if ( $address ) {
		$organization['address'] = $address;
	}

	$geo = magenta_business_geo_schema();
	if ( $geo ) {
		$organization['geo'] = $geo;
	}

	$hours = magenta_business_hours_schema();
	if ( $hours ) {
		$organization['openingHoursSpecification'] = $hours;
	}

	foreach ( array(
		'telephone'  => 'telephone',
		'email'      => 'email',
		'priceRange' => 'price_range',
	) as $prop => $key ) {
		$value = magenta_business_field( $key );
		if ( '' !== $value ) {
			$organization[ $prop ] = $value;
		}
	}

	/*
	 * LocalBusiness requires an address. Claiming the type without one is an
	 * invalid entity, so the site falls back to plain Organization until the
	 * address is filled in - accurate and narrower beats broad and broken.
	 */
	if ( ! $address ) {
		$organization['@type'] = 'Organization';
	}

	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$logo = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $logo ) {
			$organization['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $logo[0],
				'width'  => $logo[1],
				'height' => $logo[2],
			);
			$organization['image'] = $logo[0];
		}
	}

	// Falls back to the share card so the entity always carries an image.
	if ( ! isset( $organization['image'] ) ) {
		$organization['image'] = MAGENTA_URI . '/assets/img/social/og-default.jpg';
	}

	$website = array(
		'@type'           => 'WebSite',
		'@id'             => $site_id,
		'url'             => $home,
		'name'            => get_bloginfo( 'name' ),
		'description'     => get_bloginfo( 'description' ),
		'inLanguage'      => 'en',
		'publisher'       => array( '@id' => $org_id ),
		// Rule 3: the WebSite node always credits TocToc as creator.
		'creator'         => array( '@id' => MAGENTA_TOCTOC_ORG_ID ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => $home . '?s={search_term_string}',
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	$webpage = array(
		'@type'      => 'WebPage',
		'@id'        => magenta_current_url() . '#webpage',
		'url'        => magenta_current_url(),
		'name'       => wp_get_document_title(),
		'isPartOf'   => array( '@id' => $site_id ),
		'about'      => array( '@id' => $org_id ),
		'inLanguage' => 'en',
		'creator'    => array( '@id' => MAGENTA_TOCTOC_ORG_ID ),
	);

	return array( $organization, $website, $webpage );
}

function magenta_current_url(): string {
	if ( is_front_page() ) {
		return trailingslashit( home_url( '/' ) );
	}
	if ( is_singular() ) {
		return (string) get_permalink();
	}
	if ( is_post_type_archive() ) {
		return (string) get_post_type_archive_link( get_query_var( 'post_type' ) ?: 'project' );
	}
	return home_url( add_query_arg( array() ) );
}

/**
 * Print the graph. Guarded so it can only ever be emitted once per request,
 * which is what keeps the TocToc block from being duplicated if another
 * component ever calls this.
 */
function magenta_print_schema(): void {
	static $printed = false;
	if ( $printed ) {
		return;
	}
	$printed = true;

	$graph = array_merge( magenta_site_nodes(), magenta_toctoc_nodes() );

	/**
	 * Filter the full JSON-LD graph before output.
	 *
	 * @param array $graph Schema.org nodes.
	 */
	$graph = apply_filters( 'magenta_schema_graph', $graph );

	$payload = array(
		'@context' => 'https://schema.org',
		'@graph'   => array_values( $graph ),
	);

	printf(
		"<script type=\"application/ld+json\">%s</script>\n",
		wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'magenta_print_schema', 20 );
