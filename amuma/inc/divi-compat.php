<?php
/**
 * Compatibilidad con contenido creado con Divi.
 *
 * Las noticias y páginas antiguas de amuma.org están maquetadas con Divi ([et_pb_section], [et_pb_text],
 * [et_pb_gallery]…). Sin Divi activo, WordPress mostraría esos códigos como texto.
 * Aquí se registran "traductores" que convierten cada módulo en HTML limpio que el tema sabe mostrar:
 * texto → texto, galería → galería del tema (mosaico + visor), imagen → imagen, vídeo → vídeo, etc.
 *
 * Solo se activa si Divi (o su plugin Divi Builder) NO está activo.
 *
 * @package amuma
 */

defined( 'ABSPATH' ) || exit;

/**
 * Módulos que solo "envuelven" contenido: se quitan y se deja lo de dentro.
 */
function amuma_divi_wrappers() {
	return array(
		'et_pb_section', 'et_pb_row', 'et_pb_row_inner', 'et_pb_column', 'et_pb_column_inner',
		'et_pb_text', 'et_pb_fullwidth_section', 'et_pb_specialty_section', 'et_pb_group',
		'et_pb_tabs', 'et_pb_accordion', 'et_pb_slider', 'et_pb_fullwidth_slider', 'et_pb_post_slider',
		'et_pb_code', 'et_pb_fullwidth_code', 'et_pb_signup', 'et_pb_social_media_follow_network',
	);
}

/**
 * Módulos que no tienen sentido fuera de Divi (formularios, mapas, listados del propio Divi…): se eliminan.
 */
function amuma_divi_removed() {
	return array(
		'et_pb_contact_form', 'et_pb_contact_field', 'et_pb_map', 'et_pb_map_pin', 'et_pb_fullwidth_map',
		'et_pb_blog', 'et_pb_portfolio', 'et_pb_filterable_portfolio', 'et_pb_fullwidth_portfolio',
		'et_pb_post_title', 'et_pb_fullwidth_post_title', 'et_pb_post_nav', 'et_pb_comments',
		'et_pb_social_media_follow', 'et_pb_search', 'et_pb_login', 'et_pb_shop', 'et_pb_countdown_timer',
		'et_pb_circle_counter', 'et_pb_number_counter', 'et_pb_counters', 'et_pb_counter', 'et_pb_divider',
		'et_pb_menu', 'et_pb_fullwidth_menu', 'et_pb_sidebar', 'et_pb_line_break_holder',
	);
}

/**
 * Registro.
 */
function amuma_divi_register() {
	// Si Divi está activo, él se encarga.
	if ( function_exists( 'et_setup_theme' ) || defined( 'ET_BUILDER_PLUGIN_VERSION' ) || shortcode_exists( 'et_pb_section' ) ) {
		return;
	}

	foreach ( amuma_divi_wrappers() as $tag ) {
		add_shortcode( $tag, 'amuma_divi_wrapper' );
	}
	foreach ( amuma_divi_removed() as $tag ) {
		add_shortcode( $tag, '__return_empty_string' );
	}

	add_shortcode( 'et_pb_image', 'amuma_divi_image' );
	add_shortcode( 'et_pb_fullwidth_image', 'amuma_divi_image' );
	add_shortcode( 'et_pb_gallery', 'amuma_divi_gallery' );
	add_shortcode( 'et_pb_video', 'amuma_divi_video' );
	add_shortcode( 'et_pb_video_slider_item', 'amuma_divi_video' );
	add_shortcode( 'et_pb_audio', 'amuma_divi_audio' );
	add_shortcode( 'et_pb_button', 'amuma_divi_button' );
	add_shortcode( 'et_pb_blurb', 'amuma_divi_blurb' );
	add_shortcode( 'et_pb_cta', 'amuma_divi_blurb' );
	add_shortcode( 'et_pb_slide', 'amuma_divi_blurb' );
	add_shortcode( 'et_pb_tab', 'amuma_divi_blurb' );
	add_shortcode( 'et_pb_toggle', 'amuma_divi_blurb' );
	add_shortcode( 'et_pb_accordion_item', 'amuma_divi_blurb' );
	add_shortcode( 'et_pb_fullwidth_header', 'amuma_divi_blurb' );
	add_shortcode( 'et_pb_testimonial', 'amuma_divi_testimonial' );
	add_shortcode( 'et_pb_team_member', 'amuma_divi_blurb' );
	add_shortcode( 'et_pb_pricing_tables', 'amuma_divi_wrapper' );
	add_shortcode( 'et_pb_pricing_table', 'amuma_divi_blurb' );
	add_shortcode( 'et_pb_video_slider', 'amuma_divi_wrapper' );

	// Al generar extractos automáticos, WordPress borra los shortcodes con todo su contenido.
	// Con Divi eso dejaría el extracto vacío, así que los contenedores se conservan y se procesan.
	add_filter(
		'strip_shortcodes_tagnames',
		function ( $tags ) {
			return array_diff( $tags, amuma_divi_wrappers(), array( 'et_pb_blurb', 'et_pb_cta', 'et_pb_slide', 'et_pb_tab', 'et_pb_toggle', 'et_pb_accordion_item', 'et_pb_fullwidth_header', 'et_pb_testimonial', 'et_pb_team_member', 'et_pb_pricing_table', 'et_pb_pricing_tables', 'et_pb_video_slider' ) );
		}
	);
}
add_action( 'init', 'amuma_divi_register', 99 );

/**
 * Divi guarda algunos textos con saltos de línea codificados.
 */
function amuma_divi_clean( $content ) {
	$content = str_replace( array( '<!-- [et_pb_line_break_holder] -->', '[et_pb_line_break_holder]' ), "\n", (string) $content );
	return $content;
}

function amuma_divi_wrapper( $atts, $content = '' ) {
	return do_shortcode( amuma_divi_clean( $content ) );
}

function amuma_divi_image( $atts ) {
	$atts = shortcode_atts(
		array(
			'src'        => '',
			'alt'        => '',
			'title_text' => '',
			'url'        => '',
			'url_new_window' => 'off',
		),
		$atts
	);
	if ( ! $atts['src'] ) {
		return '';
	}
	$id  = attachment_url_to_postid( $atts['src'] );
	$alt = $atts['alt'] ? $atts['alt'] : '';
	$img = $id ? wp_get_attachment_image( $id, 'large', false, array( 'alt' => $alt ) ) : sprintf( '<img src="%s" alt="%s" loading="lazy">', esc_url( $atts['src'] ), esc_attr( $alt ) );
	if ( $atts['url'] ) {
		$img = sprintf( '<a href="%s"%s>%s</a>', esc_url( $atts['url'] ), 'on' === $atts['url_new_window'] ? ' target="_blank" rel="noopener"' : '', $img );
	}
	return '<figure class="wp-block-image size-large divi-image">' . $img . '</figure>';
}

function amuma_divi_gallery( $atts ) {
	$atts = shortcode_atts( array( 'gallery_ids' => '' ), $atts );
	$ids  = array_filter( array_map( 'absint', explode( ',', $atts['gallery_ids'] ) ) );
	if ( ! $ids ) {
		return '';
	}
	$html = '<figure class="wp-block-gallery has-nested-images columns-default is-cropped divi-gallery">';
	foreach ( $ids as $id ) {
		$img = wp_get_attachment_image( $id, 'large', false, array( 'class' => 'wp-image-' . $id ) );
		if ( $img ) {
			$html .= '<figure class="wp-block-image size-large">' . $img . '</figure>';
		}
	}
	return $html . '</figure>';
}

function amuma_divi_video( $atts ) {
	$atts = shortcode_atts( array( 'src' => '', 'src_webm' => '', 'image_src' => '' ), $atts );
	if ( ! $atts['src'] ) {
		return '';
	}
	// YouTube, Vimeo, Facebook… → incrustación de WordPress.
	if ( ! preg_match( '/\.(mp4|m4v|webm|ogv|mov)(\?.*)?$/i', $atts['src'] ) ) {
		$embed = wp_oembed_get( $atts['src'] );
		return $embed ? '<figure class="wp-block-embed is-type-video">' . $embed . '</figure>' : sprintf( '<p><a href="%1$s">%1$s</a></p>', esc_url( $atts['src'] ) );
	}
	return '<figure class="wp-block-video">' . wp_video_shortcode(
		array(
			'src'    => $atts['src'],
			'poster' => $atts['image_src'],
		)
	) . '</figure>';
}

function amuma_divi_audio( $atts ) {
	$atts = shortcode_atts( array( 'audio' => '', 'title' => '' ), $atts );
	return $atts['audio'] ? wp_audio_shortcode( array( 'src' => $atts['audio'] ) ) : '';
}

function amuma_divi_button( $atts ) {
	$atts = shortcode_atts( array( 'button_url' => '', 'button_text' => '', 'url_new_window' => 'off' ), $atts );
	if ( ! $atts['button_url'] || ! $atts['button_text'] ) {
		return '';
	}
	return sprintf(
		'<div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link" href="%s"%s>%s</a></div></div>',
		esc_url( $atts['button_url'] ),
		'on' === $atts['url_new_window'] ? ' target="_blank" rel="noopener"' : '',
		esc_html( $atts['button_text'] )
	);
}

function amuma_divi_blurb( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'title'       => '',
			'heading'     => '',
			'name'        => '',
			'image'       => '',
			'image_url'   => '',
			'button_text' => '',
			'button_url'  => '',
			'url'         => '',
			'subhead'     => '',
		),
		$atts
	);
	$title = $atts['title'] ? $atts['title'] : ( $atts['heading'] ? $atts['heading'] : $atts['name'] );
	$image = $atts['image'] ? $atts['image'] : $atts['image_url'];
	$out   = '<div class="divi-block">';
	if ( $image ) {
		$out .= sprintf( '<figure class="wp-block-image"><img src="%s" alt="" loading="lazy"></figure>', esc_url( $image ) );
	}
	if ( $title ) {
		$out .= '<h3>' . wp_kses_post( $title ) . '</h3>';
	}
	if ( $atts['subhead'] ) {
		$out .= '<p class="divi-block__sub">' . wp_kses_post( $atts['subhead'] ) . '</p>';
	}
	$out .= do_shortcode( amuma_divi_clean( $content ) );
	$link = $atts['button_url'] ? $atts['button_url'] : $atts['url'];
	if ( $link && $atts['button_text'] ) {
		$out .= amuma_divi_button(
			array(
				'button_url'  => $link,
				'button_text' => $atts['button_text'],
			)
		);
	}
	return $out . '</div>';
}

function amuma_divi_testimonial( $atts, $content = '' ) {
	$atts = shortcode_atts( array( 'author' => '' ), $atts );
	return '<blockquote class="wp-block-quote">' . do_shortcode( $content ) . ( $atts['author'] ? '<cite>' . esc_html( $atts['author'] ) . '</cite>' : '' ) . '</blockquote>';
}

/**
 * Limpieza final del contenido antiguo: párrafos vacíos y tamaños de letra fijos pegados desde Word/Facebook.
 */
function amuma_clean_legacy_content( $content ) {
	$content = preg_replace( '#<p>(\s|&nbsp;|<br\s*/?>)*</p>#i', '', $content );
	$content = preg_replace( '#<p>\s*(<(?:div|figure)[^>]*>)#i', '$1', $content );
	$content = preg_replace( '#(</(?:div|figure)>)\s*</p>#i', '$1', $content );
	return $content;
}
add_filter( 'the_content', 'amuma_clean_legacy_content', 20 );
