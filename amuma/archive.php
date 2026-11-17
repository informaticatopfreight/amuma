<?php
/**
 * Archivos (categorías, etiquetas, fechas).
 *
 * @package amuma
 */

get_header();
get_template_part(
	'template-parts/listing',
	null,
	array(
		'title' => wp_strip_all_tags( get_the_archive_title() ),
		'intro' => get_the_archive_description() ? get_the_archive_description() : __( 'Noticias, actividades, talleres y encuentros de AMUMA.', 'amuma' ),
	)
);
get_footer();
