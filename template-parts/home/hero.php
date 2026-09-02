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
					<dt><?php esc_html_e( 'We work with', 'magenta' ); ?></dt>
					<dd><?php esc_html_e( 'Businesses &amp; individuals', 'magenta' ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'Under one roof', 'magenta' ); ?></dt>
					<dd><?php esc_html_e( 'Design + production', 'magenta' ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'We specialize in', 'magenta' ); ?></dt>
					<dd><?php esc_html_e( 'Custom &amp; specialty', 'magenta' ); ?></dd>
				</div>
			</dl>
		</div>

		<div class="hero__media">
			<?php
			/*
			 * Real jobs, cycling. The slot that was here rendered one generated
			 * graphic; these are photographs of work the studio has actually
			 * produced, which is a stronger opening argument than any artwork.
			 *
			 * The first slide is eager and the rest are lazy, so the hero still
			 * paints on one image. Auto-advance is JS-only and never starts for a
			 * visitor who has asked for reduced motion - without JS this stays a
			 * readable stack of captioned images.
			 */
			$magenta_slides = array_values(
				array_filter(
					magenta_work_items(),
					static function ( array $item ): bool {
						return empty( $item['video'] );
					}
				)
			);
			?>
			<div class="hero__frame hero-slider" data-parallax="0.06" data-hero-slider
				role="group" aria-roledescription="<?php esc_attr_e( 'carousel', 'magenta' ); ?>"
				aria-label="<?php esc_attr_e( 'Recent work', 'magenta' ); ?>">

				<ul class="hero-slider__track">
					<?php foreach ( $magenta_slides as $magenta_i => $magenta_slide ) : ?>
						<li class="hero-slider__slide<?php echo 0 === $magenta_i ? ' is-active' : ''; ?>"
							data-hero-slide
							role="group"
							aria-roledescription="<?php esc_attr_e( 'slide', 'magenta' ); ?>"
							aria-label="<?php echo esc_attr( sprintf( '%1$d / %2$d', $magenta_i + 1, count( $magenta_slides ) ) ); ?>">
							<?php
							magenta_asset_image(
								$magenta_slide['slug'],
								$magenta_slide['alt'],
								array(
									'eager' => 0 === $magenta_i,
									'sizes' => '(max-width: 900px) 92vw, 44vw',
								)
							);
							?>
							<p class="hero-slider__caption">
								<strong><?php echo wp_kses_post( $magenta_slide['title'] ); ?></strong>
								<span><?php echo wp_kses_post( $magenta_slide['meta'] ); ?></span>
							</p>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="hero-slider__controls">
					<button class="hero-slider__arrow" type="button" data-hero-prev>
						<span class="screen-reader-text"><?php esc_html_e( 'Previous work', 'magenta' ); ?></span>
						<span aria-hidden="true">&larr;</span>
					</button>

					<div class="hero-slider__dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose work', 'magenta' ); ?>">
						<?php foreach ( $magenta_slides as $magenta_i => $magenta_slide ) : ?>
							<button class="hero-slider__dot<?php echo 0 === $magenta_i ? ' is-active' : ''; ?>"
								type="button" role="tab" data-hero-dot="<?php echo esc_attr( (string) $magenta_i ); ?>"
								aria-selected="<?php echo 0 === $magenta_i ? 'true' : 'false'; ?>">
								<span class="screen-reader-text"><?php echo esc_html( wp_strip_all_tags( $magenta_slide['title'] ) ); ?></span>
							</button>
						<?php endforeach; ?>
					</div>

					<button class="hero-slider__arrow" type="button" data-hero-next>
						<span class="screen-reader-text"><?php esc_html_e( 'Next work', 'magenta' ); ?></span>
						<span aria-hidden="true">&rarr;</span>
					</button>
				</div>
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
