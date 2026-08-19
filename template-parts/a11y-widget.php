<?php
/**
 * Accessibility menu.
 *
 * A floating control, bottom right, that lets a visitor adjust the page to suit
 * them: text size, contrast, motion, link underlines and a more readable face.
 * Every setting writes a data attribute on <html> and is remembered in
 * localStorage, so it survives navigation without a cookie notice or a plugin.
 *
 * Two things this deliberately does not do:
 *
 * - It does not claim to make the site compliant. A widget cannot do that, and
 *   overlays that advertise it have landed the sites using them in trouble. The
 *   real work is in the markup and the colour choices, which is where it has
 *   been done. This is a comfort control on top of that.
 * - It does not override a visitor's own operating-system settings. If they
 *   already ask for reduced motion, the theme honours that whether or not this
 *   panel is ever opened.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$magenta_a11y_options = array(
	'text' => array(
		'label' => __( 'Bigger text', 'magenta' ),
		'hint'  => __( 'Scales every size up by a fifth.', 'magenta' ),
	),
	'contrast' => array(
		'label' => __( 'High contrast', 'magenta' ),
		'hint'  => __( 'Darkens type and strengthens edges.', 'magenta' ),
	),
	'motion' => array(
		'label' => __( 'Pause motion', 'magenta' ),
		'hint'  => __( 'Stops the reels, the ticker and the reveals.', 'magenta' ),
	),
	'readable' => array(
		'label' => __( 'Readable font', 'magenta' ),
		'hint'  => __( 'Swaps the display face for a plainer one.', 'magenta' ),
	),
	'links' => array(
		'label' => __( 'Underline links', 'magenta' ),
		'hint'  => __( 'Marks every link, not just the ones in text.', 'magenta' ),
	),
);
?>
<div class="a11y" data-a11y>
	<button class="a11y__toggle" type="button"
		data-a11y-toggle
		aria-expanded="false"
		aria-controls="a11y-panel">
		<span class="screen-reader-text"><?php esc_html_e( 'Accessibility options', 'magenta' ); ?></span>
		<?php
		/*
		 * The secondary logo, inlined so it inherits currentColor and stays
		 * crisp at any size. Hidden from assistive technology because the
		 * button already carries its own name above.
		 */
		?>
		<img class="a11y__mark"
			src="<?php echo esc_url( MAGENTA_URI . '/assets/img/brand/logo-secondary.svg' ); ?>"
			alt="" role="presentation" width="72" height="72" loading="lazy" decoding="async">
	</button>

	<div class="a11y__panel" id="a11y-panel" data-a11y-panel hidden>
		<div class="a11y__head">
			<h2 class="a11y__title"><?php esc_html_e( 'Accessibility', 'magenta' ); ?></h2>
			<button class="a11y__close" type="button" data-a11y-close>
				<span class="screen-reader-text"><?php esc_html_e( 'Close', 'magenta' ); ?></span>
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<ul class="a11y__list">
			<?php foreach ( $magenta_a11y_options as $key => $opt ) : ?>
				<li class="a11y__item">
					<label class="a11y__option">
						<input type="checkbox" data-a11y-option="<?php echo esc_attr( $key ); ?>">
						<span class="a11y__option-text">
							<span class="a11y__option-label"><?php echo esc_html( $opt['label'] ); ?></span>
							<span class="a11y__option-hint"><?php echo esc_html( $opt['hint'] ); ?></span>
						</span>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>

		<button class="btn btn--outline a11y__reset" type="button" data-a11y-reset>
			<?php esc_html_e( 'Reset all', 'magenta' ); ?>
		</button>

		<p class="a11y__note">
			<?php esc_html_e( 'These settings are saved on this device only. If your system already asks for reduced motion, this site follows it either way.', 'magenta' ); ?>
		</p>
	</div>
</div>
