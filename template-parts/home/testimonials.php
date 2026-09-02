<?php
/**
 * Home / client words.
 *
 * Nothing here invents a client statement: each card renders as a visible
 * "pending" note until a real, approved quote is pasted into the array.
 * Publishing a made-up testimonial would be a false claim about a named
 * person, so the placeholder stays obvious on purpose.
 *
 * The quotes that are filled in are transcribed from the studio's own Google
 * reviews. They are shown as visible text only - deliberately not marked up
 * as Review schema, because Google does not support self-serving review
 * markup for a LocalBusiness describing itself, and using it invites a
 * structured-data penalty rather than a star rating.
 *
 * To fill: replace `quote`, `name` and `role`. Any card left with an empty
 * quote keeps rendering as pending, so partial fills are safe.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$quotes = apply_filters(
	'magenta_testimonials',
	array(
		array(
			'quote' => __( 'Fully recommended! B&aacute;rbara is amazing! They have done for me amazing stickers, flyers, custom shaped prints, and more! In Grand Cayman they are an amazing solution for those looking for high quality and different printing options. Also, very convenient location!', 'magenta' ),
			'name'  => __( 'Daniel Garrido', 'magenta' ),
			'role'  => __( 'Google review &middot; 5 stars', 'magenta' ),
		),
		array(
			'quote' => '',
			'name'  => '',
			'role'  => __( 'Restaurant group', 'magenta' ),
		),
		array(
			'quote' => '',
			'name'  => '',
			'role'  => __( 'Creative agency', 'magenta' ),
		),
	)
);

$has_real = (bool) array_filter( wp_list_pluck( $quotes, 'quote' ) );
?>
<section class="section section--magenta testimonials" id="words">
	<div class="wrap">

		<header class="section-head section-head--center">
			<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Kind words', 'magenta' ); ?></p>
			<h2 class="display display--lg display--light" data-reveal>
				<?php esc_html_e( 'What clients', 'magenta' ); ?> <em><?php esc_html_e( 'say.', 'magenta' ); ?></em>
			</h2>
		</header>

		<ul class="quote-grid">
			<?php foreach ( $quotes as $i => $q ) : ?>
				<li class="quote-note <?php echo $q['quote'] ? '' : 'quote-note--pending'; ?>" data-reveal style="--i:<?php echo esc_attr( (string) $i ); ?>">
					<?php if ( $q['quote'] ) : ?>
						<blockquote class="quote-note__body">
							<p><?php echo esc_html( $q['quote'] ); ?></p>
						</blockquote>
						<p class="quote-note__by">
							<strong><?php echo esc_html( $q['name'] ); ?></strong>
							<span><?php echo wp_kses_post( $q['role'] ); ?></span>
						</p>
					<?php else : ?>
						<p class="quote-note__pending">
							<?php esc_html_e( 'Quote pending', 'magenta' ); ?>
						</p>
						<p class="quote-note__hint">
							<?php echo wp_kses_post( $q['role'] ); ?>
							&mdash;
							<?php esc_html_e( 'collect and approve before launch', 'magenta' ); ?>
						</p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( ! $has_real ) : ?>
			<p class="testimonials__note">
				<?php esc_html_e( 'This section stays visibly unfinished until real, approved client quotes are in place.', 'magenta' ); ?>
			</p>
		<?php endif; ?>

	</div>
</section>
