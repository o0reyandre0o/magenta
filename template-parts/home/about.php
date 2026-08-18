<?php
/**
 * Home / studio.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section section--paper about" id="studio">
	<div class="wrap about__grid">

		<div class="about__media">
			<div class="about__frame" data-parallax="0.05">
				<?php magenta_slot_image( 'about_portrait', array( 'sizes' => '(max-width: 900px) 90vw, 42vw' ) ); ?>
			</div>
			<div class="about__frame about__frame--small" data-parallax="0.12">
				<?php magenta_slot_image( 'texture_swatches', array( 'sizes' => '(max-width: 900px) 40vw, 18vw' ) ); ?>
			</div>
			<span class="tape tape--about" aria-hidden="true"></span>
		</div>

		<div class="about__copy">
			<p class="eyebrow"><?php esc_html_e( 'The studio', 'magenta' ); ?></p>

			<h2 class="display display--md" data-reveal>
				<?php esc_html_e( 'From Barcelona', 'magenta' ); ?><br>
				<em>
					<?php
					// See the note in hero.php on why this is not run through kses.
					echo magenta_mark( __( 'to Grand Cayman.', 'magenta' ), 'underline', 'y' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</em>
			</h2>

			<div class="prose">
				<p>
					<?php esc_html_e( 'Magenta is led by Barbara, a graphic designer who learned print the long way round: on the floor of production houses in Barcelona, where a job is judged by the object that comes out at the end rather than the file that went in.', 'magenta' ); ?>
				</p>
				<p>
					<?php esc_html_e( 'That is still how the studio runs. Design and production sit in the same room, which means the person drawing the artwork already knows what the stock, the ink and the finish are going to do with it.', 'magenta' ); ?>
				</p>
				<p>
					<?php
					printf(
						/* translators: %s: highlighted phrase. */
						wp_kses_post( __( 'Today Magenta prints for a long list of the island\'s hotels, restaurants and retailers, and works as the production partner behind %s.', 'magenta' ) ),
						wp_kses_post( magenta_highlight( __( 'several of Cayman\'s creative agencies', 'magenta' ), 'c' ) )
					);
					?>
				</p>
			</div>

			<p class="about__signature" aria-hidden="true">Bárbara</p>

			<p>
				<a class="btn btn--ink" href="#contact">
					<?php esc_html_e( 'Work with us', 'magenta' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</p>
		</div>

	</div>

</section>
