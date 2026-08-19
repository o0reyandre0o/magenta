<?php
/**
 * Fallback template.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="section section--paper">
	<div class="wrap">

		<?php if ( is_singular() ) : ?>

			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article <?php post_class( 'prose' ); ?>>
					<h1 class="display display--md"><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>

		<?php elseif ( have_posts() ) : ?>

			<h1 class="display display--md">
				<?php
				if ( is_post_type_archive( 'project' ) ) {
					esc_html_e( 'Selected work', 'magenta' );
				} elseif ( is_tax() || is_category() ) {
					echo esc_html( single_term_title( '', false ) );
				} elseif ( is_search() ) {
					/* translators: %s: search query. */
					printf( esc_html__( 'Results for %s', 'magenta' ), esc_html( get_search_query() ) );
				} else {
					esc_html_e( 'Journal', 'magenta' );
				}
				?>
			</h1>

			<div class="card-grid">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<article <?php post_class( 'work-card' ); ?>>
						<a class="work-card__link" href="<?php the_permalink(); ?>">
							<div class="work-card__media">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'magenta-4x5', array( 'loading' => 'lazy' ) );
								} else {
									echo '<div class="slot-ph slot-ph--4x5"><span class="slot-ph__id">NO COVER</span></div>';
								}
								?>
							</div>
							<h2 class="work-card__title"><?php the_title(); ?></h2>
						</a>
					</article>
				<?php endwhile; ?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'prev_text' => __( 'Previous', 'magenta' ),
					'next_text' => __( 'Next', 'magenta' ),
				)
			);
			?>

		<?php else : ?>

			<h1 class="display display--md"><?php esc_html_e( 'Nothing here yet', 'magenta' ); ?></h1>
			<p class="lede"><?php esc_html_e( 'This page is still being made.', 'magenta' ); ?></p>

		<?php endif; ?>

	</div>
</section>

<?php
get_footer();
