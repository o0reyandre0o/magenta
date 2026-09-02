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

// Shared with the hero slider - see magenta_work_items() in inc/helpers.php.
$bundled = magenta_work_items();
?>
<section class="section section--ink work" id="work">
	<div class="wrap">

		<header class="section-head section-head--split">
			<div>
				<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Selected work', 'magenta' ); ?></p>
				<h2 class="display display--lg display--light" data-reveal>
					<?php esc_html_e( 'Featured', 'magenta' ); ?> <em><?php esc_html_e( 'projects.', 'magenta' ); ?></em>
				</h2>
			</div>
			<div class="section-head__aside">
				<p class="lede lede--light">
					<?php esc_html_e( 'A sample of what has left the studio lately. If you have eaten out, been to a wedding or picked up a card on this island, you have probably already held some of it.', 'magenta' ); ?>
				</p>
				<span class="work__cta-wrap">
					<a class="btn btn--outline btn--light" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ?: '#' ); ?>">
						<?php esc_html_e( 'All projects', 'magenta' ); ?>
						<span aria-hidden="true">&rarr;</span>
					</a>
					<?php magenta_doodle( 'arrow-loop', array( 'colour' => 'y', 'class' => 'doodle--work-arrow' ) ); ?>
				</span>
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
						<div class="work-card__media<?php echo empty( $card['video'] ) ? '' : ' work-card__media--video'; ?>">
							<?php
							if ( empty( $card['video'] ) ) {
								magenta_asset_image(
									$card['slug'],
									$card['alt'],
									array( 'sizes' => '(max-width: 700px) 90vw, (max-width: 1100px) 45vw, 30vw' )
								);
							} else {
								// Vertical phone footage; the card crops it rather than
								// letterboxing, so the frame matches the still cards.
								magenta_reel( $card['slug'], $card['alt'] );
							}
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
