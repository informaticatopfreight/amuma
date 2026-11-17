<?php
/**
 * Cabecera.
 *
 * @package amuma
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#e5267b">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#contenido"><?php esc_html_e( 'Saltar al contenido', 'amuma' ); ?></a>

<?php if ( is_front_page() ) : ?>
	<div class="loader" aria-hidden="true">
		<div class="loader__inner">
			<?php get_template_part( 'template-parts/ribbon', null, array( 'id' => 'loader-ribbon', 'class' => 'loader__ribbon' ) ); ?>
			<span class="loader__word">AMUMA</span>
		</div>
	</div>
<?php endif; ?>

<header class="site-header" data-header>
	<div class="site-header__inner">
		<div class="site-header__brand"><?php amuma_logo(); ?></div>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Principal', 'amuma' ); ?>" id="site-nav">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu',
					'fallback_cb'    => 'amuma_menu_fallback',
					'depth'          => 2,
				)
			);
			?>
			<div class="nav__footer">
				<?php if ( amuma_opt( 'phone1' ) ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', amuma_opt( 'phone1' ) ) ); ?>"><?php echo amuma_icon( 'phone' ); // phpcs:ignore ?> <?php echo esc_html( amuma_opt( 'phone1' ) ); ?></a>
				<?php endif; ?>
				<?php if ( amuma_opt( 'email' ) ) : ?>
					<a href="mailto:<?php echo esc_attr( antispambot( amuma_opt( 'email' ) ) ); ?>"><?php echo amuma_icon( 'mail' ); // phpcs:ignore ?> <?php echo esc_html( antispambot( amuma_opt( 'email' ) ) ); ?></a>
				<?php endif; ?>
				<?php echo amuma_social_links(); // phpcs:ignore ?>
			</div>
		</nav>

		<a class="btn btn--pink btn--sm site-header__cta" data-magnetic href="<?php echo esc_url( amuma_page_url( 'hazte-socio', '/#colabora' ) ); ?>">
			<span><?php esc_html_e( 'Hazte socia', 'amuma' ); ?></span>
		</a>

		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-menu-toggle>
			<span class="menu-toggle__label"><?php esc_html_e( 'Menú', 'amuma' ); ?></span>
			<span class="menu-toggle__lines" aria-hidden="true"><i></i><i></i></span>
		</button>
	</div>
</header>

<main id="contenido" class="site-main">
