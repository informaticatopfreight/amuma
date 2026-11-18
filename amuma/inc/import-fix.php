<?php
/**
 * Arreglos al terminar una importación (Herramientas → Importar → WordPress):
 *
 * 1. Si WordPress ha tenido que dar a una foto un número (ID) distinto del que tenía en la web
 *    de origen, corrige las referencias dentro de las noticias (galerías, imágenes).
 * 2. Si alguna noticia sigue mostrando fotos desde la web antigua (por ejemplo, porque la foto ya
 *    se había importado en un intento anterior que se cortó), la cambia por la copia local.
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

	$uploads   = wp_get_upload_dir();
	$base_dir  = trailingslashit( $uploads['basedir'] );
	$base_url  = trailingslashit( $uploads['baseurl'] );
	$this_host = wp_parse_url( home_url(), PHP_URL_HOST );

	$swap = function ( $m ) use ( $map ) {
		$id = (int) $m[2];
		return $m[1] . ( isset( $map[ $id ] ) ? $map[ $id ] : $id );
	};

	foreach ( array_unique( array_map( 'intval', $wp_import->processed_posts ) ) as $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post || ! in_array( $post->post_type, array( 'post', 'page' ), true ) ) {
			continue;
		}
		$content = $post->post_content;

		if ( $map ) {
			$content = preg_replace_callback( '/(<!-- wp:image \{"id":)(\d+)/', $swap, $content );
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
		}

		// Fotos de otra web (…/wp-content/uploads/AAAA/MM/foto.jpg) que ya existen aquí → copia local.
		$content = preg_replace_callback(
			'#https?://([^/"\'\s]+)/(?:[^"\'\s]*/)?wp-content/uploads/([^"\'\s<>)]+)#i',
			function ( $m ) use ( $this_host, $base_dir, $base_url ) {
				if ( strtolower( $m[1] ) === strtolower( (string) $this_host ) ) {
					return $m[0];
				}
				$relative = rawurldecode( $m[2] );
				if ( file_exists( $base_dir . $relative ) ) {
					return $base_url . $m[2];
				}
				// Si se guardó en otra carpeta o con "-1" al final (WordPress lo hace si el nombre ya existía),
				// se busca la foto importada por su nombre de archivo.
				global $wpdb;
				$file = basename( preg_replace( '/-\d+x\d+(\.[a-z0-9]+)$/i', '$1', $relative ) );
				$ext  = pathinfo( $file, PATHINFO_EXTENSION );
				$name = substr( $file, 0, -1 - strlen( $ext ) );
				foreach ( array( $file, $name . '-1.' . $ext, $name . '-2.' . $ext ) as $candidate ) {
					$id = (int) $wpdb->get_var(
						$wpdb->prepare(
							"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND guid LIKE %s ORDER BY ID DESC LIMIT 1",
							'%/' . $wpdb->esc_like( $candidate )
						)
					);
					if ( $id ) {
						$url = wp_get_attachment_image_url( $id, 'large' );
						return $url ? $url : wp_get_attachment_url( $id );
					}
				}
				return $m[0];
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
