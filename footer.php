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
	<div class="site-footer__bar" aria-hidden="true"><?php magenta_colour_bar( 40 ); ?></div>

	<div class="site-footer__inner">

		<div class="site-footer__brand">
			<p class="site-footer__logo">
				<?php get_template_part( 'template-parts/logo' ); ?>
				<span class="screen-reader-text">MAGENTA</span>
			</p>
			<p class="site-footer__line">
				<?php esc_html_e( 'Print production and graphic design. Grand Cayman, Cayman Islands.', 'magenta' ); ?>
			</p>
			<p class="site-footer__socials">
				<a href="https://www.instagram.com/magentacayman/" rel="me noopener" target="_blank">Instagram</a>
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

<?php wp_footer(); ?>
</body>
</html>
