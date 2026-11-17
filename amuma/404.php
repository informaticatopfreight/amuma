<?php
/**
 * Página no encontrada.
 *
 * @package amuma
 */

get_header();
?>
<section class="page-hero page-hero--404">
	<div class="page-hero__ribbon" aria-hidden="true"><?php get_template_part( 'template-parts/ribbon', null, array( 'id' => 'p404-ribbon', 'class' => 'ribbon--page' ) ); ?></div>
	<div class="container">
		<p class="kicker">404</p>
		<h1 class="page-hero__title" data-hero-title><?php esc_html_e( 'Esta página no existe', 'amuma' ); ?></h1>
		<p class="page-hero__intro"><?php esc_html_e( 'Puede que la dirección haya cambiado. Prueba a buscar o vuelve al inicio.', 'amuma' ); ?></p>
		<div class="search-404"><?php get_search_form(); ?></div>
		<p><a class="btn btn--pink" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span><?php esc_html_e( 'Volver al inicio', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></a></p>
	</div>
</section>
<?php
get_footer();
