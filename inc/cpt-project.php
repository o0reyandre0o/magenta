<?php
/**
 * Project archive: custom post type and taxonomies.
 *
 * The selected-work grid is data driven so that new jobs can be published from
 * wp-admin without touching a template. Two taxonomies because that is how the
 * work is actually browsed: by what was made, and by who it was made for.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function magenta_register_project(): void {
	register_post_type(
		'project',
		array(
			'labels'        => array(
				'name'               => __( 'Projects', 'magenta' ),
				'singular_name'      => __( 'Project', 'magenta' ),
				'add_new_item'       => __( 'Add project', 'magenta' ),
				'edit_item'          => __( 'Edit project', 'magenta' ),
				'search_items'       => __( 'Search projects', 'magenta' ),
				'not_found'          => __( 'No projects yet', 'magenta' ),
				'featured_image'     => __( 'Cover image', 'magenta' ),
				'set_featured_image' => __( 'Set cover image', 'magenta' ),
			),
			'public'        => true,
			'has_archive'   => 'work',
			'menu_icon'     => 'dashicons-images-alt2',
			'menu_position' => 20,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'rewrite'       => array( 'slug' => 'work' ),
			'show_in_rest'  => true,
		)
	);

	register_taxonomy(
		'service',
		'project',
		array(
			'labels'            => array(
				'name'          => __( 'Services', 'magenta' ),
				'singular_name' => __( 'Service', 'magenta' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'service' ),
		)
	);

	register_taxonomy(
		'sector',
		'project',
		array(
			'labels'            => array(
				'name'          => __( 'Sectors', 'magenta' ),
				'singular_name' => __( 'Sector', 'magenta' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'sector' ),
		)
	);
}
add_action( 'init', 'magenta_register_project' );

/**
 * Seed the taxonomies on first activation so the editor is never staring at an
 * empty box. Terms are created once and then left alone.
 */
function magenta_seed_terms(): void {
	if ( get_option( 'magenta_terms_seeded' ) ) {
		return;
	}

	$seed = array(
		'service' => array( 'Graphic Design', 'Brand Identity', 'Business & Marketing Print', 'Wedding & Event Stationery', 'Fine Art & Photography', 'Custom Framing', 'Artwork Digitization', 'Stickers & Labels', 'Specialty & Custom' ),
		'sector'  => array( 'Hospitality', 'Restaurants', 'Weddings & Events', 'Artists & Photographers', 'Retail', 'Agencies', 'Corporate' ),
	);

	foreach ( $seed as $tax => $terms ) {
		foreach ( $terms as $term ) {
			if ( ! term_exists( $term, $tax ) ) {
				wp_insert_term( $term, $tax );
			}
		}
	}

	update_option( 'magenta_terms_seeded', 1 );
}
add_action( 'init', 'magenta_seed_terms', 20 );

/**
 * Client name for a project, stored as simple post meta.
 */
function magenta_project_client( int $post_id = 0 ): string {
	$post_id = $post_id ?: get_the_ID();
	return (string) get_post_meta( $post_id, '_magenta_client', true );
}

function magenta_project_meta_box(): void {
	add_meta_box(
		'magenta_project_details',
		__( 'Project details', 'magenta' ),
		'magenta_project_meta_box_render',
		'project',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'magenta_project_meta_box' );

function magenta_project_meta_box_render( WP_Post $post ): void {
	wp_nonce_field( 'magenta_project_save', 'magenta_project_nonce' );
	$client = get_post_meta( $post->ID, '_magenta_client', true );
	$year   = get_post_meta( $post->ID, '_magenta_year', true );
	?>
	<p>
		<label for="magenta_client"><strong><?php esc_html_e( 'Client', 'magenta' ); ?></strong></label><br>
		<input type="text" id="magenta_client" name="magenta_client" class="widefat"
			value="<?php echo esc_attr( $client ); ?>" placeholder="e.g. Kimpton Seafire">
	</p>
	<p>
		<label for="magenta_year"><strong><?php esc_html_e( 'Year', 'magenta' ); ?></strong></label><br>
		<input type="text" id="magenta_year" name="magenta_year" class="widefat"
			value="<?php echo esc_attr( $year ); ?>" placeholder="<?php echo esc_attr( gmdate( 'Y' ) ); ?>">
	</p>
	<?php
}

function magenta_project_meta_save( int $post_id ): void {
	if ( ! isset( $_POST['magenta_project_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['magenta_project_nonce'] ) ), 'magenta_project_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, '_magenta_client', sanitize_text_field( wp_unslash( $_POST['magenta_client'] ?? '' ) ) );
	update_post_meta( $post_id, '_magenta_year', sanitize_text_field( wp_unslash( $_POST['magenta_year'] ?? '' ) ) );
}
add_action( 'save_post_project', 'magenta_project_meta_save' );
