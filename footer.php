<?php
/**
 * Site footer.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main><!-- /#main -->

<footer class="site-footer">
	<?php magenta_brand_rule( 'site-footer__bar' ); ?>

	<div class="site-footer__inner">

		<div class="site-footer__brand">
			<p class="site-footer__logo">
				<?php get_template_part( 'template-parts/logo' ); ?>
				<span class="screen-reader-text">MAGENTA</span>
			</p>
			<p class="site-footer__line">
				<?php esc_html_e( 'Design, print, framing and fine art reproduction. Grand Cayman, Cayman Islands.', 'magenta' ); ?>
			</p>
			<p class="site-footer__socials">
				<a href="https://www.instagram.com/magentacayman/" rel="me noopener" target="_blank">Instagram</a>
				<?php
				/*
				 * Published as visible text, not only inside the JSON-LD. An
				 * entity detail that a reader can see is corroboration; one
				 * that exists only in markup is an unsupported assertion.
				 */
				$magenta_email = magenta_business_field( 'email' );
				if ( $magenta_email ) :
					?>
					<a href="mailto:<?php echo esc_attr( $magenta_email ); ?>"><?php echo esc_html( $magenta_email ); ?></a>
				<?php endif; ?>
			</p>
		</div>

		<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'Footer', 'magenta' ); ?>">
			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'site-footer__list',
						'depth'          => 1,
					)
				);
			}
			?>
		</nav>

	</div>

	<div class="site-footer__legal">
		<p>
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
			<?php esc_html_e( 'All rights reserved.', 'magenta' ); ?>
		</p>
		<a class="seo-badge seo-badge--legal" href="<?php echo esc_url( MAGENTA_AGENCY_BADGE_LINK ); ?>" target="_blank" rel="nofollow noopener">
				<?php
				/*
				 * Lazy and last: the badge is rendered by admin-ajax on another
				 * host, which is an uncached round trip. Below the fold it costs
				 * nothing, but eager it would sit in the critical path of a page
				 * whose speed is already the weakest score on the audit. The
				 * intrinsic size is declared so it cannot shift the layout when
				 * it lands.
				 */
				?>
				<img src="<?php echo esc_url( MAGENTA_AGENCY_BADGE_SRC ); ?>"
					alt="<?php esc_attr_e( 'SEO score verified by TocToc Marketing', 'magenta' ); ?>"
					width="460" height="72" loading="lazy" decoding="async">
			</a>

		<p class="site-footer__credit">
			<?php
			printf(
				/* translators: %s: linked agency name. */
				esc_html__( 'Created by %s', 'magenta' ),
				sprintf(
					'<a href="%s" target="_blank" rel="noopener">%s</a>',
					esc_url( MAGENTA_AGENCY_URL ),
					esc_html( MAGENTA_AGENCY_NAME )
				)
			);
			?>
		</p>

	</div>
</footer>

<?php get_template_part( 'template-parts/whatsapp' ); ?>
<?php get_template_part( 'template-parts/a11y-widget' ); ?>

<?php wp_footer(); ?>
</body>
</html>
