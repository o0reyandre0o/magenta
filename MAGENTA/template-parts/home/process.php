<?php
/**
 * Home / process.
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
		'copy'  => __( 'We start with the finished object, not the file. Quantity, stock, budget, deadline, and where it will actually live once it leaves here.', 'magenta' ),
	),
	array(
		'n'     => '02',
		'title' => __( 'Design', 'magenta' ),
		'copy'  => __( 'Artwork built by people who know what the press will do with it. No surprises at plate stage, no last-minute redraws.', 'magenta' ),
	),
	array(
		'n'     => '03',
		'title' => __( 'Proof', 'magenta' ),
		'copy'  => __( 'You see it on the real stock before we commit. Sign off on something you can hold, not a PDF on a screen.', 'magenta' ),
	),
	array(
		'n'     => '04',
		'title' => __( 'Press', 'magenta' ),
		'copy'  => __( 'Colour matched and checked through the run. If a sheet drifts, it does not leave the building.', 'magenta' ),
	),
	array(
		'n'     => '05',
		'title' => __( 'Finish', 'magenta' ),
		'copy'  => __( 'Cut, folded, foiled, bound, packed. Delivered to the property, the restaurant or the stand, on the day it was promised.', 'magenta' ),
	),
);
?>
<section class="section section--stock process" id="process">
	<div class="wrap">

		<header class="section-head section-head--center">
			<p class="eyebrow"><?php esc_html_e( 'How it works', 'magenta' ); ?></p>
			<h2 class="display display--lg" data-reveal>
				<?php esc_html_e( 'From file to', 'magenta' ); ?> <em><?php esc_html_e( 'finish.', 'magenta' ); ?></em>
			</h2>
		</header>

		<ol class="process-list">
			<?php foreach ( $steps as $i => $step ) : ?>
				<li class="process-step" data-reveal style="--i:<?php echo esc_attr( (string) $i ); ?>">
					<span class="process-step__n" aria-hidden="true"><?php echo esc_html( $step['n'] ); ?></span>
					<div class="process-step__body">
						<h3 class="process-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
						<p><?php echo esc_html( $step['copy'] ); ?></p>
					</div>
					<span class="process-step__rule" aria-hidden="true"></span>
				</li>
			<?php endforeach; ?>
		</ol>

		<div class="process__press">
			<?php magenta_slot_image( 'hero_press', array( 'sizes' => '(max-width: 900px) 94vw, 70vw' ) ); ?>
			<span class="sticker sticker--rotate-r sticker--yellow process__sticker" data-parallax="0.1">
				<?php esc_html_e( 'proofed before it prints', 'magenta' ); ?>
			</span>
		</div>

	</div>
</section>
