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
			<?php get_template_part( 'template-parts/logo' ); ?>
		</a>

		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'magenta' ); ?>">
			<?php magenta_primary_nav( 'site-nav__list' ); ?>
		</nav>

		<a class="btn btn--paper site-header__cta" href="#contact">
			<?php esc_html_e( 'Start a job', 'magenta' ); ?>
			<span aria-hidden="true">&rarr;</span>
		</a>

		<button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="mobile-nav">
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'magenta' ); ?></span>
			<span class="nav-toggle__bar" aria-hidden="true"></span>
			<span class="nav-toggle__bar" aria-hidden="true"></span>
		</button>
	</div>

	<?php magenta_brand_rule( 'site-header__rule' ); ?>
</header>

<div class="mobile-nav" id="mobile-nav" data-mobile-nav hidden>
	<?php magenta_primary_nav( 'mobile-nav__list' ); ?>
	<a class="btn btn--paper" href="#contact"><?php esc_html_e( 'Start a job', 'magenta' ); ?></a>
</div>

<main id="main" class="site-main">
