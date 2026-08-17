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
<section class="hero section" id="top" data-reg-scope>
	<?php magenta_reg_marks(); ?>

	<div class="hero__grid wrap">

		<div class="hero__copy">
			<p class="eyebrow">
				<span class="eyebrow__dot" aria-hidden="true"></span>
				<?php esc_html_e( 'Print production & graphic design · Grand Cayman', 'magenta' ); ?>
			</p>

			<h1 class="hero__title">
				<span class="screen-reader-text">Magenta &mdash; <?php esc_html_e( 'we put ink on the island', 'magenta' ); ?></span>
				<?php magenta_cmyk_text( 'MAGENTA', 'span', 'cmyk--display' ); ?>
			</h1>

			<p class="hero__tagline" aria-hidden="true">
				<?php esc_html_e( 'We put ink', 'magenta' ); ?>
				<em><?php esc_html_e( 'on the island.', 'magenta' ); ?></em>
			</p>

			<p class="lede hero__lede">
				<?php
				printf(
					/* translators: %s: highlighted phrase. */
					wp_kses_post( __( 'Menus, packaging, signage and identity for the hotels, restaurants and agencies that %s. Designed and produced under one roof.', 'magenta' ) ),
					wp_kses_post( magenta_highlight( __( 'set the standard in Cayman', 'magenta' ) ) )
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
					<dt><?php esc_html_e( 'Turnaround', 'magenta' ); ?></dt>
					<dd><?php esc_html_e( 'Island-fast', 'magenta' ); ?></dd>
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
				<?php esc_html_e( 'PRINT IS NOT DEAD', 'magenta' ); ?>
			</span>

			<span class="sticker sticker--rotate-r sticker--cyan hero__sticker-b" data-parallax="0.2">
				<?php esc_html_e( 'ink on everything', 'magenta' ); ?>
			</span>

			<span class="tape tape--hero" aria-hidden="true"></span>
		</div>

	</div>

	<a class="hero__scroll" href="#services">
		<span><?php esc_html_e( 'Scroll', 'magenta' ); ?></span>
		<span class="hero__scroll-arrow" aria-hidden="true">&darr;</span>
	</a>
</section>
