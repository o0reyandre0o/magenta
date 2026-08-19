<?php
/**
 * Frequently asked questions, and the FAQPage graph built from them.
 *
 * Answer engines lift these close to verbatim, which makes accuracy the whole
 * point: a confident wrong answer about turnaround or minimums gets quoted back
 * to a prospect and costs the job.
 *
 * So the list is split. Questions answered by things the studio has actually
 * told us - what it makes, who it works with, how colour is managed, that short
 * runs are welcome - carry real answers and are published. Questions that
 * depend on policy nobody has confirmed - lead times, an exact minimum,
 * delivery coverage, file requirements - ship with an empty answer. Those are
 * hidden from visitors, shown to editors, and excluded from the schema, because
 * FAQPage markup must mirror text that actually exists on the page.
 *
 * To publish one: write its answer. Nothing else is needed.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array<int, array{q:string, a:string}>
 */
function magenta_faq_items(): array {
	$items = array(

		/* ------------------------------------------ Answerable from the brief */
		array(
			'q' => __( 'What does Magenta do?', 'magenta' ),
			'a' => __( 'Magenta is a boutique graphic design and production studio in Grand Cayman. Design and production sit in the same room, so we take a project from the first idea through to the finished physical object rather than handing a file to someone else to make.', 'magenta' ),
		),
		array(
			'q' => __( 'What can Magenta make?', 'magenta' ),
			'a' => __( 'Business cards, brochures, booklets, stationery, menus, posters and folders; wedding and event stationery from invitations through to signage; fine art and photography printing including giclée, canvas and framed work; vinyl and die-cut stickers and labels; and specialty pieces using foil, white ink, soft-touch lamination, embossing and custom die-cut shapes.', 'magenta' ),
		),
		array(
			'q' => __( 'Do you design as well as produce?', 'magenta' ),
			'a' => __( 'Yes, and that is the point of the studio. Branding and visual identity, marketing collateral, editorial design, wedding and event stationery, menu design and print-ready artwork. You can arrive with a finished file or with nothing but an idea.', 'magenta' ),
		),
		array(
			'q' => __( 'Do you offer custom framing?', 'magenta' ),
			'a' => __( 'Yes. Custom picture framing for fine art and photography, canvas and floater framing, custom matting and mounting, and help choosing the frame and materials. Because we print in-house too, a piece can be printed and framed in the same place.', 'magenta' ),
		),
		array(
			'q' => __( 'Can you digitize and reproduce original artwork?', 'magenta' ),
			'a' => __( 'Yes. We scan original artwork at high quality, colour correct it, prepare it for reproduction and print archival fine art reproductions. Artists use this to turn one original into a print run without losing the character of the original.', 'magenta' ),
		),
		array(
			'q' => __( 'Do you take on small quantities and one-off pieces?', 'magenta' ),
			'a' => __( 'Yes. We are not working from a catalogue of fixed products and sizes, and many pieces are finished or assembled by hand, which is what makes short runs and single custom pieces possible. It is a large part of why weddings, artists and small businesses come to us.', 'magenta' ),
		),
		array(
			'q' => __( 'How do you make sure the colour is right?', 'magenta' ),
			'a' => __( 'We run a colour-managed workflow from design through to production, using spectrophotometer-based calibration and custom colour profiles for our monitors, printers and materials. That is what makes the colour on the finished print predictable rather than a surprise.', 'magenta' ),
		),
		array(
			'q' => __( 'Can you make something that is not a standard print product?', 'magenta' ),
			'a' => __( 'That is the work we enjoy most. We develop non-standard pieces, source and test materials, prototype, combine different materials and production methods, and work to custom sizes and shapes. If an idea does not fit a standard print catalogue, bring it to us.', 'magenta' ),
		),
		array(
			'q' => __( 'Where is Magenta based?', 'magenta' ),
			'a' => __( 'Grand Cayman, in the Cayman Islands. We work with businesses, individuals, artists, photographers, restaurants, hospitality brands, weddings and events across the island.', 'magenta' ),
		),

		/* ------------------------------------ Awaiting confirmation from Magenta
		 * Do not write answers to these from assumption. Each one is a
		 * commitment a prospect will hold the studio to.
		 */
		array(
			'q' => __( 'What is your typical turnaround time?', 'magenta' ),
			'a' => '',
		),
		array(
			'q' => __( 'Is there a minimum order quantity?', 'magenta' ),
			'a' => '',
		),
		array(
			'q' => __( 'Do you deliver across the island?', 'magenta' ),
			'a' => '',
		),
		array(
			'q' => __( 'What file formats should I supply?', 'magenta' ),
			'a' => '',
		),
	);

	/**
	 * Filter the FAQ list.
	 *
	 * @param array $items Question and answer pairs.
	 */
	return apply_filters( 'magenta_faq_items', $items );
}

/**
 * Only the entries with a real answer.
 *
 * @return array<int, array{q:string, a:string}>
 */
function magenta_faq_answered(): array {
	return array_values(
		array_filter(
			magenta_faq_items(),
			static function ( array $item ): bool {
				return '' !== trim( $item['a'] );
			}
		)
	);
}

/**
 * Add the FAQPage node to the graph on the front page.
 *
 * Only answered questions go in. Markup that does not mirror visible page text
 * is a structured-data violation, and an unanswered question has no text to
 * mirror.
 */
function magenta_faq_schema( array $graph ): array {
	if ( ! is_front_page() ) {
		return $graph;
	}

	$answered = magenta_faq_answered();
	if ( ! $answered ) {
		return $graph;
	}

	$questions = array();
	foreach ( $answered as $item ) {
		$questions[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $item['q'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $item['a'] ),
			),
		);
	}

	$graph[] = array(
		'@type'      => 'FAQPage',
		'@id'        => trailingslashit( home_url( '/' ) ) . '#faq',
		'isPartOf'   => array( '@id' => trailingslashit( home_url( '/' ) ) . '#website' ),
		'inLanguage' => 'en',
		'mainEntity' => $questions,
	);

	return $graph;
}
add_filter( 'magenta_schema_graph', 'magenta_faq_schema' );
