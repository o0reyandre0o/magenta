<?php
/**
 * Document head and site header.
 *
 * @package Magenta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#EA028C">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'magenta' ); ?></a>

<!-- Paper grain sits above the background and below the content, site wide. -->
<div class="grain" aria-hidden="true"></div>

<header class="site-header" data-header>
	<div class="site-header__inner">

		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> &mdash; home">
			<span class="brand__mark" aria-hidden="true">
				<svg viewBox="0 0 32 32" width="26" height="26" fill="none">
					<circle cx="16" cy="16" r="10" stroke="currentColor" stroke-width="2"/>
					<path d="M16 2v28M2 16h28" stroke="currentColor" stroke-width="2"/>
				</svg>
			</span>
			<span class="brand__word">MAGENTA</span>
		</a>

		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'magenta' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'site-nav__list',
						'depth'          => 1,
					)
				);
			} else {
				// Placeholder navigation until the menu is built in wp-admin.
				echo '<ul class="site-nav__list">';
				$fallback = array(
					'#work'     => __( 'Work', 'magenta' ),
					'#services' => __( 'Services', 'magenta' ),
					'#process'  => __( 'Process', 'magenta' ),
					'#studio'   => __( 'Studio', 'magenta' ),
				);
				foreach ( $fallback as $href => $label ) {
					printf( '<li><a href="%s">%s</a></li>', esc_url( $href ), esc_html( $label ) );
				}
				echo '</ul>';
			}
			?>
		</nav>

		<a class="btn btn--ink site-header__cta" href="#contact">
			<?php esc_html_e( 'Start a job', 'magenta' ); ?>
			<span aria-hidden="true">&rarr;</span>
		</a>

		<button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="mobile-nav">
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'magenta' ); ?></span>
			<span class="nav-toggle__bar" aria-hidden="true"></span>
			<span class="nav-toggle__bar" aria-hidden="true"></span>
		</button>
	</div>

	<div class="site-header__rule" aria-hidden="true"><?php magenta_colour_bar( 40 ); ?></div>
</header>

<div class="mobile-nav" id="mobile-nav" data-mobile-nav hidden>
	<?php
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-nav__list',
				'depth'          => 1,
			)
		);
	}
	?>
	<a class="btn btn--magenta" href="#contact"><?php esc_html_e( 'Start a job', 'magenta' ); ?></a>
</div>

<main id="main" class="site-main">
