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

	<div class="wrap cta__grid">

		<div class="cta__copy">
			<p class="eyebrow"><?php esc_html_e( 'Start a job', 'magenta' ); ?></p>

			<h2 class="cta__title">
				<span class="screen-reader-text"><?php esc_html_e( 'Let us make something you can hold.', 'magenta' ); ?></span>
				<?php magenta_cmyk_text( 'LET’S PRINT', 'span', 'cmyk--display' ); ?>
				<em aria-hidden="true"><?php esc_html_e( 'something real.', 'magenta' ); ?></em>
			</h2>

			<p class="lede">
				<?php esc_html_e( 'Tell us what you need made, how many, and when it has to be in someone\'s hands. We will come back with a route and a price.', 'magenta' ); ?>
			</p>

			<ul class="cta__list">
				<li><?php esc_html_e( 'Grand Cayman, Cayman Islands', 'magenta' ); ?></li>
				<li><a href="https://www.instagram.com/magentacayman/" target="_blank" rel="noopener">@magentacayman</a></li>
			</ul>

			<span class="sticker sticker--rotate-l sticker--cyan cta__sticker" data-parallax="0.1">
				<?php esc_html_e( 'quotes within 24h', 'magenta' ); ?>
			</span>
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
						$options = array(
							'Offset printing',
							'Large format',
							'Screen printing',
							'Foil & finishing',
							'Packaging',
							'Signage',
							'Brand identity',
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
