<?php
/**
 * Al terminar una importación (Herramientas → Importar → WordPress), corrige las referencias
 * a imágenes dentro del contenido cuando WordPress ha tenido que dar a una foto un número (ID)
 * distinto del que tenía en la web de origen. Sin esto, alguna galería antigua podría salir rota.
 *
 * @package amuma
 */

defined( 'ABSPATH' ) || exit;

function amuma_fix_imported_ids() {
	global $wp_import, $wpdb;
	if ( empty( $wp_import ) || empty( $wp_import->processed_posts ) ) {
		return;
	}
	$map = array();
	foreach ( $wp_import->processed_posts as $old => $new ) {
		if ( (int) $old !== (int) $new ) {
			$map[ (int) $old ] = (int) $new;
		}
	}
	if ( ! $map ) {
		return;
	}
	foreach ( array_unique( array_map( 'intval', $wp_import->processed_posts ) ) as $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post || ! in_array( $post->post_type, array( 'post', 'page' ), true ) ) {
			continue;
		}
		$swap    = function ( $m ) use ( $map ) {
			$id = (int) $m[2];
			return $m[1] . ( isset( $map[ $id ] ) ? $map[ $id ] : $id );
		};
		$content = preg_replace_callback( '/(<!-- wp:image \{"id":)(\d+)/', $swap, $post->post_content );
		$content = preg_replace_callback( '/(wp-image-)(\d+)/', $swap, $content );
		$content = preg_replace_callback(
			'/(gallery_ids=["\'])([\d,]+)/',
			function ( $m ) use ( $map ) {
				$ids = array_map(
					function ( $id ) use ( $map ) {
						$id = (int) $id;
						return isset( $map[ $id ] ) ? $map[ $id ] : $id;
					},
					explode( ',', $m[2] )
				);
				return $m[1] . implode( ',', $ids );
			},
			$content
		);
		if ( $content !== $post->post_content ) {
			$wpdb->update( $wpdb->posts, array( 'post_content' => $content ), array( 'ID' => $post_id ) );
			clean_post_cache( $post_id );
		}
	}
}
add_action( 'import_end', 'amuma_fix_imported_ids' );
