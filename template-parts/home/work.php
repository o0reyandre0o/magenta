<?php
/**
 * Home / selected work.
 *
 * Driven by the `project` post type. Until projects are published it falls back
 * to placeholder cards carrying the brief for the shoot, so the section holds
 * its shape and reads as intentional rather than empty.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$projects = new WP_Query(
	array(
		'post_type'           => 'project',
		'posts_per_page'      => 6,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

/**
 * Real jobs, shipped with the theme.
 *
 * These are the client's own photographs of work already produced, so the grid
 * shows genuine output from launch rather than placeholders. Publishing any
 * `project` post replaces this list entirely - see the query above.
 *
 * `alt` describes the photograph for anyone who cannot see it; it is not a
 * repeat of the title.
 */
$bundled = array(
	array(
		'slug'   => 'coffee-cart-cards',
		'title'  => __( 'The Coffee Cart', 'magenta' ),
		'client' => __( 'Mobile coffee &amp; matcha', 'magenta' ),
		'meta'   => __( 'Business cards &middot; Uncoated stock', 'magenta' ),
		'alt'    => __( 'Two stacks of square business cards, one black and one cream, showing a retro running coffee-cup mascot.', 'magenta' ),
	),
	array(
		'slug'   => 'amuse-bouche-menu',
		'title'  => __( 'Amuse Bouche', 'magenta' ),
		'client' => __( 'Restaurant', 'magenta' ),
		'meta'   => __( 'Tasting menu &middot; Die-cut &middot; Coloured stock', 'magenta' ),
		'alt'    => __( 'A tasting menu standing open beside a red die-cut envelope closed with a small paper heart.', 'magenta' ),
	),
	array(
		'slug'   => 'goddess-beer',
		'title'  => __( 'Goddess Hazy IPA', 'magenta' ),
		'client' => __( '19&middot;81 Brewing Co.', 'magenta' ),
		'meta'   => __( 'Coasters &middot; Die-cut &middot; Brochure', 'magenta' ),
		'alt'    => __( 'Round pink beer coasters and a matching folded brochure for a collaborative brew.', 'magenta' ),
	),
	array(
		'slug'   => 'anytime-wellness-cards',
		'title'  => __( 'Anytime Wellness', 'magenta' ),
		'client' => __( 'Wellness', 'magenta' ),
		'meta'   => __( 'Gift cards &middot; Gold foil', 'magenta' ),
		'alt'    => __( 'Stacks of white gift cards stamped in gold foil, styled with dried flowers and a wooden salt scoop.', 'magenta' ),
	),
	array(
		'slug'   => 'lalique-brochures',
		'title'  => __( '60 Lalique', 'magenta' ),
		'client' => __( 'Property', 'magenta' ),
		'meta'   => __( 'Property brochure &middot; Offset', 'magenta' ),
		'alt'    => __( 'A fanned stack of property brochures for a Crystal Harbour home, opened to interior photography.', 'magenta' ),
	),
	array(
		'slug'   => 'align-brochures',
		'title'  => __( 'Align', 'magenta' ),
		'client' => __( 'Healthcare', 'magenta' ),
		'meta'   => __( 'Brochure suite &middot; Offset', 'magenta' ),
		'alt'    => __( 'A spread of purple and white healthcare brochures for adult and paediatric therapy services.', 'magenta' ),
	),
);
?>
<section class="section section--ink work" id="work">
	<div class="wrap">

		<header class="section-head section-head--split">
			<div>
				<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Selected work', 'magenta' ); ?></p>
				<h2 class="display display--lg display--light" data-reveal>
					<?php esc_html_e( 'Things you can', 'magenta' ); ?> <em><?php esc_html_e( 'hold.', 'magenta' ); ?></em>
				</h2>
			</div>
			<div class="section-head__aside">
				<p class="lede lede--light">
					<?php esc_html_e( 'A sample of what has come off the press lately. If you have eaten out, checked in or walked past a window on this island, you have probably already seen some of it.', 'magenta' ); ?>
				</p>
				<a class="btn btn--outline btn--light" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ?: '#' ); ?>">
					<?php esc_html_e( 'All projects', 'magenta' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</div>
		</header>

		<div class="work-grid">
			<?php if ( $projects->have_posts() ) : ?>

				<?php
				$i = 0;
				while ( $projects->have_posts() ) :
					$projects->the_post();
					$client   = magenta_project_client();
					$services = get_the_term_list( get_the_ID(), 'service', '', ' &middot; ' );
					?>
					<article class="work-card" data-reveal style="--i:<?php echo esc_attr( (string) $i ); ?>">
						<a class="work-card__link" href="<?php the_permalink(); ?>">
							<div class="work-card__media">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail(
										'magenta-4x5',
										array(
											'loading' => 'lazy',
											'sizes'   => '(max-width: 700px) 90vw, 30vw',
										)
									);
								} else {
									echo '<div class="slot-ph slot-ph--4x5"><span class="slot-ph__id">' . esc_html__( 'COVER PENDING', 'magenta' ) . '</span></div>';
								}
								?>
								<span class="tape tape--card" aria-hidden="true"></span>
							</div>
							<div class="work-card__body">
								<h3 class="work-card__title"><?php the_title(); ?></h3>
								<?php if ( $client ) : ?>
									<p class="work-card__client"><?php echo esc_html( $client ); ?></p>
								<?php endif; ?>
								<?php if ( $services && ! is_wp_error( $services ) ) : ?>
									<p class="work-card__meta"><?php echo wp_kses_post( wp_strip_all_tags( $services ) ); ?></p>
								<?php endif; ?>
							</div>
						</a>
					</article>
					<?php
					++$i;
				endwhile;
				wp_reset_postdata();
				?>

			<?php else : ?>

				<?php foreach ( $bundled as $i => $card ) : ?>
					<article class="work-card work-card--bundled" data-reveal style="--i:<?php echo esc_attr( (string) $i ); ?>">
						<div class="work-card__media">
							<?php
							magenta_asset_image(
								$card['slug'],
								$card['alt'],
								array( 'sizes' => '(max-width: 700px) 90vw, (max-width: 1100px) 45vw, 30vw' )
							);
							?>
							<span class="tape tape--card" aria-hidden="true"></span>
						</div>
						<div class="work-card__body">
							<h3 class="work-card__title"><?php echo wp_kses_post( $card['title'] ); ?></h3>
							<p class="work-card__client"><?php echo wp_kses_post( $card['client'] ); ?></p>
							<p class="work-card__meta"><?php echo wp_kses_post( $card['meta'] ); ?></p>
						</div>
					</article>
				<?php endforeach; ?>

			<?php endif; ?>
		</div>

	</div>
</section>
