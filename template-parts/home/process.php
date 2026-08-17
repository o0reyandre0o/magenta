<?php
/**
 * Home / process.
 *
 * Structured as a sheet travelling through the machine rather than as a list
 * of bullet points: the press shot runs full bleed with the title over it, and
 * the stations below are threaded onto a rail that fills as you scroll, the
 * way paper advances through the press.
 *
 * The numbering is load-bearing here - these are sequential stations a job
 * actually passes through, in order, and the reader needs that order.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps = array(
	array(
		'n'     => '01',
		'title' => __( 'Brief', 'magenta' ),
		'note'  => __( 'Before anything opens', 'magenta' ),
		'copy'  => __( 'We start with the finished object, not the file. Quantity, stock, budget, deadline, and where it will actually live once it leaves here.', 'magenta' ),
	),
	array(
		'n'     => '02',
		'title' => __( 'Design', 'magenta' ),
		'note'  => __( 'Artwork &amp; prepress', 'magenta' ),
		'copy'  => __( 'Artwork built by people who know what the press will do with it. No surprises at plate stage, no last-minute redraws.', 'magenta' ),
	),
	array(
		'n'     => '03',
		'title' => __( 'Proof', 'magenta' ),
		'note'  => __( 'On the real stock', 'magenta' ),
		'copy'  => __( 'You see it on the real stock before we commit. Sign off on something you can hold, not a PDF on a screen.', 'magenta' ),
	),
	array(
		'n'     => '04',
		'title' => __( 'Press', 'magenta' ),
		'note'  => __( 'Colour held through the run', 'magenta' ),
		'copy'  => __( 'Colour matched and checked through the run. If a sheet drifts, it does not leave the building.', 'magenta' ),
	),
	array(
		'n'     => '05',
		'title' => __( 'Finish', 'magenta' ),
		'note'  => __( 'Out the door', 'magenta' ),
		'copy'  => __( 'Cut, folded, foiled, bound, packed. Delivered to the property, the restaurant or the stand, on the day it was promised.', 'magenta' ),
	),
);
?>
<section class="process" id="process">

	<!-- Full-bleed press band: the blurred sheet coming off the machine, with
	     the section title sitting on it. -->
	<div class="process__band">
		<div class="process__band-media" data-parallax="0.04">
			<?php magenta_slot_image( 'hero_press', array( 'sizes' => '100vw' ) ); ?>
		</div>

		<div class="process__band-inner wrap">
			<p class="eyebrow eyebrow--light"><?php esc_html_e( 'How it works', 'magenta' ); ?></p>
			<h2 class="process__title" data-reveal>
				<?php esc_html_e( 'From file', 'magenta' ); ?>
				<span class="process__title-rule" aria-hidden="true"></span>
				<em><?php esc_html_e( 'to finish.', 'magenta' ); ?></em>
			</h2>
			<p class="process__standfirst">
				<?php esc_html_e( 'Five stations. A job does not skip any of them, and nothing leaves the building that has not cleared all five.', 'magenta' ); ?>
			</p>
		</div>

		<div class="process__band-bar" aria-hidden="true"><?php magenta_colour_bar( 48 ); ?></div>
	</div>

	<!-- Stations, threaded onto a rail that fills with the scroll. -->
	<div class="process__stations wrap" data-rail>
		<div class="process__rail" aria-hidden="true">
			<span class="process__rail-fill"></span>
		</div>

		<ol class="process-list">
			<?php foreach ( $steps as $i => $step ) : ?>
				<li class="station" data-reveal style="--i:<?php echo esc_attr( (string) $i ); ?>">
					<span class="station__dot" aria-hidden="true"></span>

					<span class="station__n" aria-hidden="true"><?php echo esc_html( $step['n'] ); ?></span>

					<div class="station__body">
						<p class="station__note"><?php echo wp_kses_post( $step['note'] ); ?></p>
						<h3 class="station__title"><?php echo esc_html( $step['title'] ); ?></h3>
						<p class="station__copy"><?php echo esc_html( $step['copy'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>

		<p class="process__close">
			<span class="sticker sticker--rotate-r sticker--yellow">
				<?php esc_html_e( 'proofed before it prints', 'magenta' ); ?>
			</span>
		</p>
	</div>
</section>
