<?php
/**
 * Home / process.
 *
 * How a custom piece actually gets made here, in order: the conversation, the
 * design, the materials, the proof, the making. The sequence this replaced was
 * a press run - plates, colour drift, sheets leaving the building - which
 * described a print shop rather than a design and production studio.
 *
 * The numbering is load-bearing: these are sequential, and the reader needs
 * that order.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps = array(
	array(
		'n'     => '01',
		'title' => __( 'Conversation', 'magenta' ),
		'note'  => __( 'Before anything is drawn', 'magenta' ),
		'copy'  => __( 'Tell us what the piece has to do and who it is for. You do not need to arrive with a spec - working out the format, the material and the method is our half of the job.', 'magenta' ),
	),
	array(
		'n'     => '02',
		'title' => __( 'Design', 'magenta' ),
		'note'  => __( 'Drawn to be made', 'magenta' ),
		'copy'  => __( 'Artwork built by someone who already knows how it will be produced. The stock, the finish and the construction are decided with the design, not after it.', 'magenta' ),
	),
	array(
		'n'     => '03',
		'title' => __( 'Materials', 'magenta' ),
		'note'  => __( 'Chosen, sourced, tested', 'magenta' ),
		'copy'  => __( 'Paper, board, canvas, vinyl, foil, framing materials. We keep a curated selection and source specifically when a project needs something we do not stock.', 'magenta' ),
	),
	array(
		'n'     => '04',
		'title' => __( 'Proof', 'magenta' ),
		'note'  => __( 'On the real material', 'magenta' ),
		'copy'  => __( 'You approve something you can hold. Our monitors, printers and materials are profiled with a spectrophotometer, so what you sign off is what comes back.', 'magenta' ),
	),
	array(
		'n'     => '05',
		'title' => __( 'Made', 'magenta' ),
		'note'  => __( 'Often by hand', 'magenta' ),
		'copy'  => __( 'Printed, cut, foiled, bound, mounted or framed. Many pieces are finished and assembled by hand, which is what makes short runs and one-offs possible at all.', 'magenta' ),
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
				<?php esc_html_e( 'Five steps from a conversation to something you can hold. Nothing skips ahead, and nothing goes out that we would not put our own name on.', 'magenta' ); ?>
			</p>
		</div>

		<?php magenta_brand_rule( 'process__band-bar' ); ?>
	</div>

	<!-- Stations, threaded onto a rail that fills with the scroll. -->
	<?php
	/*
	 * Brand shapes behind the steps. A flat dark slab with a rail down it was
	 * the whole background before, which read as an empty grey panel once the
	 * ink token moved to the brand's #333. These carry the identity's own
	 * device instead, tinted and mirrored so they do not read as one stamp
	 * repeated.
	 */
	?>
	<div class="process__shapes" aria-hidden="true">
		<?php magenta_blob( 'blob--process-a', '' ); ?>
		<?php magenta_blob( 'blob--process-b', 'h' ); ?>
		<?php magenta_blob( 'blob--process-c', 'hv' ); ?>
	</div>

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
