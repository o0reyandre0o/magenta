<?php
/**
 * Home / hero.
 *
 * The headline is a four-colour separation. It lands out of register and pulls
 * into alignment as the section scrolls - the whole brand idea in one gesture,
 * and it needs no photography to work.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="hero section" id="top">

	<div class="hero__shapes" aria-hidden="true">
		<?php magenta_blob( 'blob--hero-a', '' ); ?>
		<?php magenta_blob( 'blob--hero-b', 'hv' ); ?>
	</div>

	<div class="hero__grid wrap">

		<div class="hero__copy">
			<p class="eyebrow">
				<span class="eyebrow__dot" aria-hidden="true"></span>
				<?php esc_html_e( 'Design &amp; production studio · Grand Cayman', 'magenta' ); ?>
			</p>

			<h1 class="hero__title">
				<?php esc_html_e( 'From idea to', 'magenta' ); ?>
				<em>
					<?php
					/*
					 * Echoed raw rather than through wp_kses_post, which strips
					 * svg and path outright. Safe: magenta_mark() escapes the
					 * text itself and the shape comes from a fixed set in
					 * inc/doodles.php, never from input.
					 */
					echo magenta_mark( __( 'finished piece.', 'magenta' ), 'underline', 'y' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</em>
			</h1>

			<p class="lede hero__lede">
				<?php
				printf(
					/* translators: %s: highlighted phrase. */
					wp_kses_post( __( 'Design, print, framing and fine art reproduction for businesses, weddings, artists and photographers %s.', 'magenta' ) ),
					wp_kses_post( magenta_highlight( __( 'across the Cayman Islands', 'magenta' ) ) )
				);
				?>
			</p>

			<p class="hero__actions">
				<a class="btn btn--magenta" href="#work">
					<?php esc_html_e( 'See the work', 'magenta' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
				<a class="btn btn--outline" href="#contact">
					<?php esc_html_e( 'Start a job', 'magenta' ); ?>
				</a>
			</p>

			<dl class="hero__facts">
				<div>
					<dt><?php esc_html_e( 'Based in', 'magenta' ); ?></dt>
					<dd><?php esc_html_e( 'Grand Cayman', 'magenta' ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'Trained in', 'magenta' ); ?></dt>
					<dd><?php esc_html_e( 'Barcelona', 'magenta' ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'Under one roof', 'magenta' ); ?></dt>
					<dd><?php esc_html_e( 'Design + production', 'magenta' ); ?></dd>
				</div>
			</dl>
		</div>

		<div class="hero__media">
			<div class="hero__frame" data-parallax="0.06">
				<?php
				magenta_slot_image(
					'hero_main',
					array(
						'eager' => true,
						'sizes' => '(max-width: 900px) 92vw, 44vw',
					)
				);
				?>
			</div>

			<span class="sticker sticker--rotate-l sticker--yellow hero__sticker-a" data-parallax="0.14">
				<?php esc_html_e( 'CROP IT LIKE IT&rsquo;S HOT', 'magenta' ); ?>
			</span>

			<span class="sticker sticker--rotate-r sticker--teal hero__sticker-b" data-parallax="0.2">
				<?php esc_html_e( 'pixel perfect', 'magenta' ); ?>
			</span>

			<span class="tape tape--hero" aria-hidden="true"></span>

			<?php magenta_doodle( 'asterisk', array( 'colour' => 'teal', 'class' => 'doodle--hero-star' ) ); ?>
			<?php magenta_doodle( 'arrow', array( 'colour' => 'm', 'class' => 'doodle--hero-arrow' ) ); ?>
		</div>

	</div>

	<a class="hero__scroll" href="#services">
		<span><?php esc_html_e( 'Scroll', 'magenta' ); ?></span>
		<span class="hero__scroll-arrow" aria-hidden="true">&darr;</span>
	</a>
</section>
