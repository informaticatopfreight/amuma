<?php
/**
 * Funciones de apoyo para las plantillas.
 *
 * @package amuma
 */

defined( 'ABSPATH' ) || exit;

/**
 * Devuelve la imagen principal de una entrada.
 * Orden: imagen destacada → primera imagen subida a la entrada → primera <img> del contenido.
 * Así, aunque se olviden de poner "imagen destacada", la tarjeta sale con foto.
 *
 * @return array{url:string,alt:string,id:int}|null
 */
function amuma_post_image( $post = null, $size = 'amuma-card' ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return null;
	}

	$id = get_post_thumbnail_id( $post );

	if ( ! $id ) {
		$attached = get_attached_media( 'image', $post->ID );
		if ( $attached ) {
			$first = reset( $attached );
			$id    = $first->ID;
		}
	}

	if ( ! $id && preg_match( '/wp-image-(\d+)/', $post->post_content, $m ) ) {
		$id = (int) $m[1];
	}

	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, $size );
		if ( $src ) {
			return array(
				'id'  => (int) $id,
				'url' => $src[0],
				'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
			);
		}
	}

	if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/i', $post->post_content, $m ) ) {
		return array(
			'id'  => 0,
			'url' => $m[1],
			'alt' => '',
		);
	}

	return null;
}

/**
 * Imprime la imagen de la tarjeta (con srcset si es un adjunto de WordPress).
 */
function amuma_post_image_html( $post = null, $size = 'amuma-card', $attr = array() ) {
	$img = amuma_post_image( $post, $size );
	if ( ! $img ) {
		return '<span class="media-fallback" aria-hidden="true">' . amuma_ribbon_mini() . '</span>';
	}
	$attr = wp_parse_args(
		$attr,
		array(
			'loading' => 'lazy',
			'alt'     => $img['alt'] ? $img['alt'] : get_the_title( $post ),
		)
	);
	if ( $img['id'] ) {
		return wp_get_attachment_image( $img['id'], $size, false, $attr );
	}
	return sprintf( '<img src="%s" alt="%s" loading="lazy">', esc_url( $img['url'] ), esc_attr( $attr['alt'] ) );
}

/**
 * Número de fotos de una entrada (para la etiqueta "12 fotos" de las tarjetas).
 */
function amuma_photo_count( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return 0;
	}
	$count = preg_match_all( '/<img\s/i', $post->post_content );
	// Galerías clásicas [gallery ids="1,2,3"].
	if ( preg_match_all( '/\[gallery[^\]]*ids=["\']([^"\']+)["\']/', $post->post_content, $m ) ) {
		foreach ( $m[1] as $ids ) {
			$count += count( array_filter( explode( ',', $ids ) ) );
		}
	}
	return $count;
}

/**
 * Categoría principal (la primera) de la entrada.
 */
function amuma_primary_category( $post = null ) {
	$cats = get_the_category( $post ? get_post( $post )->ID : 0 );
	if ( empty( $cats ) ) {
		return null;
	}
	// Prioriza Noticias / Actividades si están.
	foreach ( $cats as $c ) {
		if ( in_array( $c->slug, array( 'actividades', 'noticias' ), true ) ) {
			return $c;
		}
	}
	return $cats[0];
}

/**
 * Logo: el que suban en Personalizar → Identidad del sitio; si no, lazo + nombre.
 */
function amuma_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="brand" href="%1$s" rel="home">%2$s<span class="brand__name">%3$s</span></a>',
		esc_url( home_url( '/' ) ),
		amuma_ribbon_mini(), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : 'AMUMA' )
	);
}

/**
 * Lazo pequeño, plano (para logo, separadores y tarjetas sin foto).
 */
function amuma_ribbon_mini( $class = 'ribbon-mini' ) {
	return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 200 300" aria-hidden="true" focusable="false"><path d="M100 26 C 70 26, 56 62, 66 104 C 76 150, 112 196, 150 284" fill="none" stroke="currentColor" stroke-width="34" opacity=".75"/><path d="M96 26.2 C 98 26, 99 26, 100 26 C 130 26, 144 62, 134 104 C 124 150, 88 196, 50 284" fill="none" stroke="currentColor" stroke-width="34"/></svg>';
}

/**
 * Iconos SVG sencillos.
 */
function amuma_icon( $name ) {
	$icons = array(
		'arrow'     => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-up'  => '<path d="M7 17 17 7M8 7h9v9"/>',
		'heart'     => '<path d="M12 20s-7-4.35-7-10a4 4 0 0 1 7-2.65A4 4 0 0 1 19 10c0 5.65-7 10-7 10Z"/>',
		'body'      => '<circle cx="12" cy="5" r="2.2"/><path d="M5 9h14M12 9v5m0 0-3 6m3-6 3 6"/>',
		'info'      => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/>',
		'people'    => '<circle cx="8" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M2.5 19c.8-3.4 3-5 5.5-5s4.7 1.6 5.5 5M14 14.5c2.6-.6 5.6.6 6.5 4.5"/>',
		'phone'     => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'pin'       => '<path d="M12 21s-7-6.2-7-12a7 7 0 0 1 14 0c0 5.8-7 12-7 12Z"/><circle cx="12" cy="9" r="2.5"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'camera'    => '<path d="M4 8h3l2-3h6l2 3h3v11H4z"/><circle cx="12" cy="13" r="3.5"/>',
		'facebook'  => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v2H7v4h3v7h4v-7h3l1-4h-4V8z"/>',
		'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5v.01"/>',
		'x'         => '<path d="M4 4l16 16M20 4 4 20"/>',
		'youtube'   => '<rect x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="m10 9 5 3-5 3z"/>',
		'whatsapp'  => '<path d="M4 20l1.3-4A8 8 0 1 1 8 18.7z"/><path d="M9 9.5c.5 2.5 2.5 4.5 5 5l1.2-1.3 2 1-.5 1.5c-3.5.5-8-4-7.5-7.5l1.5-.5 1 2z"/>',
		'link'      => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
		'close'     => '<path d="M6 6l12 12M18 6 6 18"/>',
		'chevron-l' => '<path d="m15 5-7 7 7 7"/>',
		'chevron-r' => '<path d="m9 5 7 7-7 7"/>',
	);
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg class="icon icon-' . esc_attr( $name ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/**
 * Menú por defecto si aún no se ha creado ninguno en Apariencia → Menús.
 */
function amuma_menu_fallback() {
	$items = array(
		home_url( '/' )                => __( 'Inicio', 'amuma' ),
		home_url( '/#quienes-somos' )  => __( 'Quiénes somos', 'amuma' ),
		home_url( '/#que-hacemos' )    => __( 'Qué hacemos', 'amuma' ),
		amuma_news_url()               => __( 'Actualidad', 'amuma' ),
		home_url( '/#contacto' )       => __( 'Contacto', 'amuma' ),
	);
	echo '<ul class="menu">';
	foreach ( $items as $url => $label ) {
		printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * URL del listado de Actualidad (la "Página de entradas" de Ajustes → Lectura).
 */
function amuma_news_url() {
	$page_for_posts = (int) get_option( 'page_for_posts' );
	if ( $page_for_posts ) {
		return get_permalink( $page_for_posts );
	}
	return home_url( '/?post_type=post' );
}

/**
 * Enlaces de redes sociales configurados.
 */
function amuma_social_links() {
	$out = '';
	foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'x' => 'X', 'youtube' => 'YouTube' ) as $key => $label ) {
		$url = amuma_opt( $key );
		if ( $url ) {
			$out .= sprintf( '<a class="social__link" href="%s" target="_blank" rel="noopener" aria-label="%s">%s</a>', esc_url( $url ), esc_attr( $label ), amuma_icon( $key ) );
		}
	}
	return $out ? '<div class="social">' . $out . '</div>' : '';
}

/**
 * Convierte "a, b, c" en un array limpio.
 */
function amuma_list( $value ) {
	return array_values( array_filter( array_map( 'trim', explode( ',', (string) $value ) ) ) );
}

/**
 * Pone en cursiva (y en rosa) la última palabra de un título: "No estás <em>sola.</em>"
 */
function amuma_em_last_word( $text ) {
	$text  = trim( (string) $text );
	$parts = preg_split( '/\s+/u', $text );
	if ( count( $parts ) < 2 ) {
		return esc_html( $text );
	}
	$last = array_pop( $parts );
	return esc_html( implode( ' ', $parts ) ) . ' <em>' . esc_html( $last ) . '</em>';
}
