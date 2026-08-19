<?php
/**
 * Home / what we make.
 *
 * These are the categories Magenta actually sells, taken from the studio's own
 * brand brief. The section this replaced advertised offset, screen printing,
 * large format, packaging and signage - a print shop's department list, none
 * of which the studio offers. Anything named here has to survive a client
 * ringing up and asking for it.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = array(
	array(
		'slot'  => 'service_business',
		'title' => __( 'Business &amp; marketing', 'magenta' ),
		'copy'  => __( 'Business cards, brochures, booklets and catalogues, stationery, menus, posters, folders. The everyday pieces, made like they matter.', 'magenta' ),
		'tag'   => '01',
	),
	array(
		'slot'  => 'service_weddings',
		'title' => __( 'Weddings &amp; events', 'magenta' ),
		'copy'  => __( 'Invitation suites, save the dates, menus and programs, place cards, table numbers, seating charts, welcome signs, coasters and tags.', 'magenta' ),
		'tag'   => '02',
	),
	array(
		'slot'  => 'service_fineart',
		'title' => __( 'Fine art &amp; photography', 'magenta' ),
		'copy'  => __( 'Archival giclée and canvas prints, artist reproductions, and scanning and colour correction of original work. Colour managed end to end.', 'magenta' ),
		'tag'   => '03',
	),
	array(
		'slot'  => 'service_stickers',
		'title' => __( 'Stickers &amp; labels', 'magenta' ),
		'copy'  => __( 'Vinyl stickers, die-cut and custom shapes, laminated labels. Short runs welcome — you do not need to order a thousand.', 'magenta' ),
		'tag'   => '04',
	),
	array(
		'slot'  => 'service_specialty',
		'title' => __( 'Specialty &amp; custom', 'magenta' ),
		'copy'  => __( 'Foil, white specialty ink, soft-touch lamination, embossing, die-cut shapes and binding. Bespoke pieces and short-run custom production.', 'magenta' ),
		'tag'   => '05',
	),
	array(
		'slot'  => 'service_framing',
		'title' => __( 'Custom framing', 'magenta' ),
		'copy'  => __( 'Picture framing for fine art and photography, canvas and floater framing, custom matting and mounting, frame and material selection.', 'magenta' ),
		'tag'   => '06',
	),
);
?>
<section class="section section--paper services" id="services">
	<div class="wrap">

		<header class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'What we make', 'magenta' ); ?></p>
			<h2 class="display display--lg" data-reveal>
				<?php esc_html_e( 'From idea to', 'magenta' ); ?>
				<em>
					<?php
					// See the note in hero.php on why this is not run through kses.
					echo magenta_mark( __( 'finished piece.', 'magenta' ), 'underline', 'teal' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</em>
			</h2>
			<p class="lede section-head__lede">
				<?php esc_html_e( 'Design and production live in the same studio, so we already know how a piece will be made while we are still drawing it. Come to us with an idea, not a production plan.', 'magenta' ); ?>
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
				<?php esc_html_e( 'Not sure which one you need? That is what the first conversation is for.', 'magenta' ); ?>
			</span>
		</p>

	</div>
</section>
