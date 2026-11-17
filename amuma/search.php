<?php
/**
 * Resultados de búsqueda.
 *
 * @package amuma
 */

get_header();
get_template_part(
	'template-parts/listing',
	null,
	array(
		/* translators: %s: término buscado */
		'title' => sprintf( __( 'Búsqueda: %s', 'amuma' ), get_search_query() ),
		'intro' => '',
	)
);
get_footer();
