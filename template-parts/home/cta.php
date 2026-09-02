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
<section class="section section--paper cta" id="contact">

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
				<?php esc_html_e( 'Let’s make', 'magenta' ); ?>
				<em><?php esc_html_e( 'something real.', 'magenta' ); ?></em>
			</h2>

			<p class="lede">
				<?php esc_html_e( 'Tell us what you want made, roughly how many, and when it has to exist. If you are not sure how it should be produced, that is fine - say so and we will work it out.', 'magenta' ); ?>
			</p>

			<ul class="cta__list">
				<li><?php esc_html_e( 'Grand Cayman, Cayman Islands', 'magenta' ); ?></li>
				<li><a href="https://www.instagram.com/magentacayman/" target="_blank" rel="noopener">@magentacayman</a></li>
			</ul>

			<span class="sticker sticker--rotate-l sticker--teal cta__sticker" data-parallax="0.1">
				<?php esc_html_e( 'quotes within 24h', 'magenta' ); ?>
			</span>

			<?php magenta_doodle( 'arrow', array( 'colour' => 'm', 'class' => 'doodle--cta-arrow' ) ); ?>
			<?php magenta_doodle( 'sparkle', array( 'colour' => 'y', 'class' => 'doodle--cta-sparkle' ) ); ?>
		</div>

		<div class="cta__contact">
			<?php
			/*
			 * Contact details rather than a form. A form asks for effort before
			 * the studio has given anything back; these let someone start the
			 * conversation in whichever channel they already use.
			 *
			 * Every line below is emitted only when it holds a real value - the
			 * same rule the schema graph follows, so nothing here can advertise a
			 * phone number or an address that has not been entered in
			 * Appearance > Magenta Business.
			 */
			$magenta_email = magenta_business_field( 'email' );
			$magenta_phone = magenta_business_field( 'telephone' );
			?>

			<ul class="contact-list">
				<?php if ( $magenta_email ) : ?>
					<li class="contact-list__item">
						<span class="contact-list__label"><?php esc_html_e( 'Email', 'magenta' ); ?></span>
						<a class="contact-list__value" href="mailto:<?php echo esc_attr( $magenta_email ); ?>"><?php echo esc_html( $magenta_email ); ?></a>
					</li>
				<?php endif; ?>

				<?php if ( $magenta_phone ) : ?>
					<li class="contact-list__item">
						<span class="contact-list__label"><?php esc_html_e( 'Phone', 'magenta' ); ?></span>
						<a class="contact-list__value" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $magenta_phone ) ); ?>"><?php echo esc_html( $magenta_phone ); ?></a>
					</li>
				<?php endif; ?>

				<li class="contact-list__item">
					<span class="contact-list__label"><?php esc_html_e( 'Instagram', 'magenta' ); ?></span>
					<a class="contact-list__value" href="https://www.instagram.com/magentacayman/" target="_blank" rel="me noopener">@magentacayman</a>
				</li>

				<li class="contact-list__item">
					<span class="contact-list__label"><?php esc_html_e( 'Studio', 'magenta' ); ?></span>
					<span class="contact-list__value"><?php esc_html_e( 'Grand Cayman, Cayman Islands', 'magenta' ); ?></span>
				</li>
			</ul>

			<?php
			/*
			 * The studio's own Google Maps embed, supplied by the client. Using
			 * their URL rather than one built from an address means the pin is on
			 * the verified Google Business listing, not on a guess - and it works
			 * before the address fields are filled in.
			 *
			 * Lazy: it is an iframe to another origin, well below the fold, and
			 * eager it would pull Google's payload into the critical path.
			 */
			?>
			<div class="contact-map">
				<iframe
					title="<?php esc_attr_e( 'Map showing Magenta Creative Studio in Grand Cayman', 'magenta' ); ?>"
					src="<?php echo esc_url( MAGENTA_MAP_EMBED ); ?>"
					width="600" height="450" loading="lazy"
					referrerpolicy="strict-origin-when-cross-origin"
					allowfullscreen></iframe>
			</div>
		</div>

	</div>
</section>
