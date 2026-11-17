<?php
/**
 * Listado de entradas (la página "Actualidad").
 *
 * @package amuma
 */

get_header();
$amuma_page = (int) get_option( 'page_for_posts' );
get_template_part(
	'template-parts/listing',
	null,
	array(
		'title' => $amuma_page ? get_the_title( $amuma_page ) : __( 'Actualidad', 'amuma' ),
		'intro' => ( $amuma_page && has_excerpt( $amuma_page ) ) ? get_the_excerpt( $amuma_page ) : __( 'Noticias, actividades, talleres y encuentros de AMUMA.', 'amuma' ),
	)
);
get_footer();
