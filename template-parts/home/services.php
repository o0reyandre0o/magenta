<?php
/**
 * Home / services.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = array(
	array(
		'slot'  => 'service_offset',
		'title' => __( 'Offset printing', 'magenta' ),
		'copy'  => __( 'Long runs with the colour held exactly where you signed it off. Menus, brochures, stationery, publications.', 'magenta' ),
		'tag'   => '01',
	),
	array(
		'slot'  => 'service_large',
		'title' => __( 'Large format', 'magenta' ),
		'copy'  => __( 'Banners, window graphics, vehicle wraps and event build. Printed, finished and installed across the island.', 'magenta' ),
		'tag'   => '02',
	),
	array(
		'slot'  => 'service_screen',
		'title' => __( 'Screen printing', 'magenta' ),
		'copy'  => __( 'Uniforms, merchandise, tote bags and staff kit. Ink that survives the salt, the sun and the wash.', 'magenta' ),
		'tag'   => '03',
	),
	array(
		'slot'  => 'service_finishing',
		'title' => __( 'Foil &amp; finishing', 'magenta' ),
		'copy'  => __( 'Hot foil, emboss, deboss, spot UV, die-cutting. The part guests notice before they read a word.', 'magenta' ),
		'tag'   => '04',
	),
	array(
		'slot'  => 'service_packaging',
		'title' => __( 'Packaging', 'magenta' ),
		'copy'  => __( 'Structural design through to production. Boxes, sleeves, labels and bags that make the product feel worth it.', 'magenta' ),
		'tag'   => '05',
	),
	array(
		'slot'  => 'service_signage',
		'title' => __( 'Signage', 'magenta' ),
		'copy'  => __( 'Wayfinding, dimensional letters, acrylic and metal. Specified, fabricated and mounted.', 'magenta' ),
		'tag'   => '06',
	),
);
?>
<section class="section section--paper services" id="services">
	<div class="wrap">

		<header class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'What we make', 'magenta' ); ?></p>
			<h2 class="display display--lg" data-reveal>
				<?php esc_html_e( 'Ink on', 'magenta' ); ?>
				<em>
					<?php
					// See the note in hero.php on why this is not run through kses.
					echo magenta_mark( __( 'everything.', 'magenta' ), 'underline', 'c' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</em>
				<?php magenta_doodle( 'squiggle', array( 'colour' => 'm', 'class' => 'doodle--services' ) ); ?>
			</h2>
			<p class="lede section-head__lede">
				<?php esc_html_e( 'Design and production are the same conversation here. Nothing gets handed over a wall, which is why nothing comes back wrong.', 'magenta' ); ?>
			</p>
		</header>

		<ul class="service-grid">
			<?php foreach ( $services as $i => $service ) : ?>
				<li class="service-card" data-reveal style="--i:<?php echo esc_attr( (string) $i ); ?>">
					<div class="service-card__media">
						<?php magenta_slot_image( $service['slot'], array( 'sizes' => '(max-width: 700px) 90vw, 30vw' ) ); ?>
						<span class="service-card__tag" aria-hidden="true"><?php echo esc_html( $service['tag'] ); ?></span>
					</div>
					<div class="service-card__body">
						<h3 class="service-card__title"><?php echo wp_kses_post( $service['title'] ); ?></h3>
						<p><?php echo esc_html( $service['copy'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<p class="services__note">
			<span class="sticker sticker--rotate-l sticker--magenta">
				<?php esc_html_e( 'Not sure which one you need? That is what the first call is for.', 'magenta' ); ?>
			</span>
		</p>

	</div>
</section>
