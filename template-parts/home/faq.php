<?php
/**
 * Home / frequently asked questions.
 *
 * This section is the visible half of inc/faq.php. The two are not optional
 * for each other: FAQPage structured data is only valid when the same question
 * and answer text is on the page for a visitor to read, so the schema in
 * inc/faq.php is a policy violation without this markup rendered alongside it.
 *
 * Questions are real headings rather than an accordion. Answer engines lift a
 * heading with the answer directly beneath it far more reliably than they lift
 * a control they have to understand the state of, and there is no JavaScript
 * in the path to fail.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$magenta_faq_answered = magenta_faq_answered();

if ( ! $magenta_faq_answered ) {
	return;
}

/*
 * The questions still waiting on a policy decision from the studio are shown
 * only to someone who can act on them. A prospect seeing "we have not decided
 * our turnaround yet" is worse than not raising the question at all, but the
 * gap still needs to be visible to the person who can close it.
 */
$magenta_faq_pending = array();
if ( current_user_can( 'edit_theme_options' ) ) {
	foreach ( magenta_faq_items() as $magenta_item ) {
		if ( '' === trim( $magenta_item['a'] ) ) {
			$magenta_faq_pending[] = $magenta_item;
		}
	}
}
?>
<section class="section section--stock faq" id="faq">
	<div class="wrap">

		<header class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Before you ask', 'magenta' ); ?></p>
			<h2 class="display display--lg" data-reveal>
				<?php esc_html_e( 'The questions', 'magenta' ); ?>
				<em>
					<?php
					// See the note in hero.php on why this is not run through kses.
					echo magenta_mark( __( 'everyone asks.', 'magenta' ), 'underline', 'm' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</em>
			</h2>
			<p class="lede section-head__lede">
				<?php esc_html_e( 'Straight answers, no quote form in the way. If what you need is not here, ask us directly.', 'magenta' ); ?>
			</p>
		</header>

		<div class="faq-list">
			<?php foreach ( $magenta_faq_answered as $magenta_i => $magenta_item ) : ?>
				<article class="faq-item" data-reveal style="--i:<?php echo esc_attr( (string) $magenta_i ); ?>">
					<h3 class="faq-item__q">
						<span class="faq-item__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $magenta_i + 1 ) ); ?></span>
						<?php echo esc_html( $magenta_item['q'] ); ?>
					</h3>
					<p class="faq-item__a"><?php echo esc_html( $magenta_item['a'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( $magenta_faq_pending ) : ?>
			<div class="faq-pending" role="note">
				<p class="faq-pending__head">
					<?php esc_html_e( 'Only you can see this. These questions are live in the site but unanswered, so they are hidden from visitors and left out of the structured data.', 'magenta' ); ?>
				</p>
				<ul>
					<?php foreach ( $magenta_faq_pending as $magenta_item ) : ?>
						<li><?php echo esc_html( $magenta_item['q'] ); ?></li>
					<?php endforeach; ?>
				</ul>
				<p class="faq-pending__how">
					<?php esc_html_e( 'To publish one, write its answer in inc/faq.php. Nothing else is needed.', 'magenta' ); ?>
				</p>
			</div>
		<?php endif; ?>

	</div>
</section>
