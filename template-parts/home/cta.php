<?php
/**
 * Home / closing call to action.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section section--paper cta" id="contact" data-reg-scope>
	<?php magenta_reg_marks(); ?>

	<?php
	/*
	 * Closing background. The slot was written into the brief as "Closing
	 * section background" but no template ever output it, so the shot could
	 * have been taken and had nowhere to land.
	 *
	 * It sits under a heavy paper wash rather than at full strength: the
	 * photograph does not exist yet, so the section cannot assume it will be
	 * light in the places the type needs it to be. The form on top carries its
	 * own opaque ground, so only the copy column depends on the wash.
	 */
	?>
	<div class="cta__bg" aria-hidden="true">
		<?php magenta_slot_image( 'cta_background', array( 'sizes' => '100vw' ) ); ?>
	</div>

	<div class="wrap cta__grid">

		<div class="cta__copy">
			<p class="eyebrow"><?php esc_html_e( 'Start a job', 'magenta' ); ?></p>

			<h2 class="cta__title">
				<span class="screen-reader-text"><?php esc_html_e( 'Let us make something you can hold.', 'magenta' ); ?></span>
				<?php magenta_cmyk_text( 'LET’S PRINT', 'span', 'cmyk--display' ); ?>
				<em aria-hidden="true"><?php esc_html_e( 'something real.', 'magenta' ); ?></em>
			</h2>

			<p class="lede">
				<?php esc_html_e( 'Tell us what you want made, roughly how many, and when it has to exist. If you are not sure how it should be produced, that is fine - say so and we will work it out.', 'magenta' ); ?>
			</p>

			<ul class="cta__list">
				<li><?php esc_html_e( 'Grand Cayman, Cayman Islands', 'magenta' ); ?></li>
				<li><a href="https://www.instagram.com/magentacayman/" target="_blank" rel="noopener">@magentacayman</a></li>
			</ul>

			<span class="sticker sticker--rotate-l sticker--cyan cta__sticker" data-parallax="0.1">
				<?php esc_html_e( 'quotes within 24h', 'magenta' ); ?>
			</span>

			<?php magenta_doodle( 'arrow', array( 'colour' => 'm', 'class' => 'doodle--cta-arrow' ) ); ?>
			<?php magenta_doodle( 'sparkle', array( 'colour' => 'y', 'class' => 'doodle--cta-sparkle' ) ); ?>
		</div>

		<div class="cta__form-wrap">
			<form class="print-form" data-contact-form novalidate>
				<div class="print-form__row">
					<label for="mf-name"><?php esc_html_e( 'Name', 'magenta' ); ?></label>
					<input type="text" id="mf-name" name="name" required autocomplete="name">
				</div>

				<div class="print-form__row">
					<label for="mf-email"><?php esc_html_e( 'Email', 'magenta' ); ?></label>
					<input type="email" id="mf-email" name="email" required autocomplete="email">
				</div>

				<div class="print-form__row">
					<label for="mf-company"><?php esc_html_e( 'Company', 'magenta' ); ?></label>
					<input type="text" id="mf-company" name="company" autocomplete="organization">
				</div>

				<div class="print-form__row">
					<label for="mf-service"><?php esc_html_e( 'What do you need?', 'magenta' ); ?></label>
					<select id="mf-service" name="service">
						<option value=""><?php esc_html_e( 'Select…', 'magenta' ); ?></option>
						<?php
						// Must stay in step with template-parts/home/services.php.
						$options = array(
							'Business & marketing print',
							'Wedding or event stationery',
							'Fine art or photography printing',
							'Custom framing',
							'Stickers & labels',
							'Specialty or custom production',
							'Artwork digitization',
							'Graphic design / branding',
							'Not sure yet',
						);
						foreach ( $options as $option ) {
							printf(
								'<option value="%1$s">%1$s</option>',
								esc_attr( wp_specialchars_decode( $option ) )
							);
						}
						?>
					</select>
				</div>

				<div class="print-form__row">
					<label for="mf-message"><?php esc_html_e( 'The job', 'magenta' ); ?></label>
					<textarea id="mf-message" name="message" rows="4"
						placeholder="<?php esc_attr_e( 'Quantity, deadline, anything you already know.', 'magenta' ); ?>"></textarea>
				</div>

				<?php // Honeypot: real people never fill this in. ?>
				<div class="print-form__trap" aria-hidden="true">
					<label for="mf-website"><?php esc_html_e( 'Leave this empty', 'magenta' ); ?></label>
					<input type="text" id="mf-website" name="website" tabindex="-1" autocomplete="off">
				</div>

				<button class="btn btn--magenta btn--block" type="submit">
					<?php esc_html_e( 'Send it', 'magenta' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</button>

				<p class="print-form__status" data-form-status role="status" aria-live="polite"></p>
			</form>
		</div>

	</div>
</section>
