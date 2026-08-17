<?php
/**
 * Home / reels.
 *
 * The client's own vertical clips of work being made. They are 9:16 phone
 * footage, so they get a strip of upright cards rather than a wide band -
 * cropping them to landscape would throw away most of the frame.
 *
 * Playback only starts when a clip is actually on screen, and never at all
 * under prefers-reduced-motion; see initReels() in assets/js/main.js.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$reels = array(
	array(
		'slug'  => 'vinyl-stickers',
		'title' => __( 'Vinyl stickers', 'magenta' ),
		'meta'  => __( 'Cut to shape', 'magenta' ),
		'alt'   => __( 'Vinyl stickers being weeded and peeled after contour cutting.', 'magenta' ),
	),
	array(
		'slug'  => 'festive-diecut',
		'title' => __( 'Festive die-cut', 'magenta' ),
		'meta'  => __( 'Printed &amp; cut in house', 'magenta' ),
		'alt'   => __( 'Custom die-cut seasonal pieces stacked and fanned out on a shelf.', 'magenta' ),
	),
	array(
		'slug'  => 'wellness-gift',
		'title' => __( 'Gift cards', 'magenta' ),
		'meta'  => __( 'Foiled &amp; boxed', 'magenta' ),
		'alt'   => __( 'Foiled wellness gift cards being handled and arranged.', 'magenta' ),
	),
);
?>
<section class="section section--stock reels" id="reels">
	<div class="wrap">

		<header class="section-head section-head--split">
			<div>
				<p class="eyebrow"><?php esc_html_e( 'On the floor', 'magenta' ); ?></p>
				<h2 class="display display--lg" data-reveal>
					<?php esc_html_e( 'Watch it', 'magenta' ); ?> <em><?php esc_html_e( 'get made.', 'magenta' ); ?></em>
				</h2>
			</div>
			<div class="section-head__aside">
				<p class="lede">
					<?php esc_html_e( 'Short clips from the workshop. No sound, no edit, nothing staged - just the machines doing the part that is hard to describe in words.', 'magenta' ); ?>
				</p>
			</div>
		</header>

		<ul class="reel-strip">
			<?php foreach ( $reels as $i => $reel ) : ?>
				<li class="reel" data-reveal style="--i:<?php echo esc_attr( (string) $i ); ?>">
					<div class="reel__frame">
						<?php magenta_reel( $reel['slug'], $reel['alt'] ); ?>
						<span class="reel__badge" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					</div>
					<div class="reel__body">
						<h3 class="reel__title"><?php echo esc_html( $reel['title'] ); ?></h3>
						<p class="reel__meta"><?php echo wp_kses_post( $reel['meta'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
