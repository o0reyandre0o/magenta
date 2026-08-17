<?php
/**
 * Frequently asked questions, and the FAQPage graph built from them.
 *
 * Answer engines lift these close to verbatim, which makes accuracy the whole
 * point: a confident wrong answer about turnaround or minimum quantities gets
 * quoted back to a prospect and costs the job.
 *
 * So the list is split. Questions answered from what the site already states -
 * the services offered, the proofing step, who the studio works with - carry
 * real answers and are published. Questions that depend on business policy the
 * studio has not told us - lead times, minimums, delivery coverage, file
 * requirements - ship with an empty answer. Those render as a visible prompt
 * and are excluded from the schema entirely, because FAQPage markup must
 * mirror text that actually exists on the page.
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

		/* ------------------------------------------- Answerable from the site */
		array(
			'q' => __( 'What can Magenta print?', 'magenta' ),
			'a' => __( 'Offset printing for menus, brochures, stationery and publications; large format for banners, window graphics and vehicle wraps; screen printing for uniforms and merchandise; foil, embossing, spot UV and die-cutting; packaging from structural design through production; and signage including wayfinding and dimensional letters.', 'magenta' ),
		),
		array(
			'q' => __( 'Do you design as well as print?', 'magenta' ),
			'a' => __( 'Yes. Design and production sit in the same studio, so the person drawing the artwork already knows what the stock, the ink and the finish will do with it. You can come to us with a finished file or with nothing but an idea.', 'magenta' ),
		),
		array(
			'q' => __( 'Where is Magenta based?', 'magenta' ),
			'a' => __( 'Grand Cayman, in the Cayman Islands. We produce for businesses across the island.', 'magenta' ),
		),
		array(
			'q' => __( 'Can I see a proof before the full run prints?', 'magenta' ),
			'a' => __( 'Yes, and we insist on it. You sign off on a proof printed on the real stock, not a PDF on a screen, so the thing you approve is the thing you receive.', 'magenta' ),
		),
		array(
			'q' => __( 'Who does Magenta work with?', 'magenta' ),
			'a' => __( 'Hotels, restaurants, retailers and creative agencies across the Cayman Islands. Several local agencies use Magenta as their production partner, printing work that goes out under their own name.', 'magenta' ),
		),
		array(
			'q' => __( 'Do you work with agencies as a white-label production partner?', 'magenta' ),
			'a' => __( 'Yes. A number of Cayman agencies bring us their clients\' print work. We produce it; you present it.', 'magenta' ),
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
