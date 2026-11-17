<?php
/**
 * Textos editables de la portada y datos de contacto.
 * Se cambian en Apariencia → Personalizar → "AMUMA · Portada" y "AMUMA · Contacto".
 *
 * @package amuma
 */

defined( 'ABSPATH' ) || exit;

/**
 * Valores por defecto. Revisad los marcados con (CONFIRMAR) con la asociación.
 */
function amuma_defaults() {
	return array(
		// Portada · cabecera.
		'hero_eyebrow'   => 'Asociación de Cáncer de Mama y Ginecológico de Castilla-La Mancha',
		'hero_title'     => 'No estás sola.',
		'hero_text'      => 'Desde 1997 acompañamos a las personas con cáncer de mama y ginecológico, y a sus familias, en cada paso del camino. Desde Ciudad Real, para toda Castilla-La Mancha.',
		'hero_btn1'      => 'Pide ayuda',
		'hero_btn1_url'  => '#contacto',
		'hero_btn2'      => 'Hazte socia',
		'hero_btn2_url'  => '/hazte-socio/',

		// Cinta en movimiento.
		'marquee'        => 'Apoyo, Información, Acompañamiento, Prevención, Investigación, Juntas',

		// Quiénes somos.
		'about_kicker'   => 'Quiénes somos',
		'about_text'     => 'Somos un grupo, mayoritariamente de mujeres, que decidimos compartir, informar, aprender a vivir con la enfermedad y sus secuelas, reivindicar nuestros derechos y sensibilizar a la sociedad.',
		'stat1_num'      => '1997',
		'stat1_suffix'   => '',
		'stat1_label'    => 'año en que nació AMUMA',
		'stat2_num'      => '6',
		'stat2_suffix'   => '',
		'stat2_label'    => 'servicios de atención: psicología, fisioterapia, nutrición, oncoestética, podología y ayudas',
		'stat3_num'      => '19',
		'stat3_suffix'   => ' oct',
		'stat3_label'    => 'Día del Cáncer de Mama: fuimos la primera asociación que pidió celebrarlo en España',

		// Qué hacemos (los servicios se editan en inc/content.php).
		'services_title' => 'Cómo te ayudamos',

		// Colabora.
		'cta_title'      => 'Súmate al lazo',
		'cta1_title'     => 'Hazte socia',
		'cta1_text'      => 'Por solo 6 € al mes formas parte de AMUMA y tienes acceso a todos nuestros servicios.',
		'cta1_url'       => '/hazte-socio/',
		'cta2_title'     => 'Haz un donativo',
		'cta2_text'      => 'Con muy poco puedes hacer mucho. Todas las aportaciones suman.',
		'cta2_url'       => '/hazte-socio/#donativos',
		'cta3_title'     => 'Voluntariado',
		'cta3_text'      => 'Tu tiempo puede ser el apoyo que otra persona necesita.',
		'cta3_url'       => '/contacto/',

		// Contacto.
		'address'        => "Ronda de Calatrava 18, local\n(esquina con C/ Felipe II)\n13003 Ciudad Real",
		'phone1'         => '926 21 65 79',
		'phone2'         => '636 90 13 77',
		'email'          => 'info@amuma.org',
		'hours'          => 'Lunes a viernes, de 9:00 a 13:00',
		'maps_url'       => 'https://www.google.com/maps/search/?api=1&query=38.9894508,-3.9216924',
		'facebook'       => 'https://www.facebook.com/AMUMAOficial',
		'instagram'      => 'https://www.instagram.com/amuma_oficial/',
		'x'              => 'https://twitter.com/AMUMA_Oficial',
		'youtube'        => '',
	);
}

/**
 * Lee una opción con su valor por defecto.
 */
function amuma_opt( $key ) {
	$defaults = amuma_defaults();
	return get_theme_mod( 'amuma_' . $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

/**
 * Registro en el Personalizador.
 */
function amuma_customize_register( $wp_customize ) {
	$d = amuma_defaults();

	$wp_customize->add_panel(
		'amuma',
		array(
			'title'    => __( 'AMUMA', 'amuma' ),
			'priority' => 20,
		)
	);

	$sections = array(
		'amuma_hero'     => array(
			'title'  => __( 'Portada · Cabecera', 'amuma' ),
			'fields' => array(
				'hero_eyebrow'  => array( __( 'Texto pequeño superior', 'amuma' ), 'text' ),
				'hero_title'    => array( __( 'Título grande', 'amuma' ), 'text' ),
				'hero_text'     => array( __( 'Texto', 'amuma' ), 'textarea' ),
				'hero_btn1'     => array( __( 'Botón 1 · texto', 'amuma' ), 'text' ),
				'hero_btn1_url' => array( __( 'Botón 1 · enlace', 'amuma' ), 'url' ),
				'hero_btn2'     => array( __( 'Botón 2 · texto', 'amuma' ), 'text' ),
				'hero_btn2_url' => array( __( 'Botón 2 · enlace', 'amuma' ), 'url' ),
				'marquee'       => array( __( 'Cinta en movimiento (palabras separadas por comas)', 'amuma' ), 'text' ),
			),
		),
		'amuma_about'    => array(
			'title'  => __( 'Portada · Quiénes somos', 'amuma' ),
			'fields' => array(
				'about_kicker' => array( __( 'Etiqueta', 'amuma' ), 'text' ),
				'about_text'   => array( __( 'Texto destacado', 'amuma' ), 'textarea' ),
				'stat1_num'    => array( __( 'Cifra 1 · número', 'amuma' ), 'text' ),
				'stat1_suffix' => array( __( 'Cifra 1 · sufijo (+, %…)', 'amuma' ), 'text' ),
				'stat1_label'  => array( __( 'Cifra 1 · texto', 'amuma' ), 'text' ),
				'stat2_num'    => array( __( 'Cifra 2 · número', 'amuma' ), 'text' ),
				'stat2_suffix' => array( __( 'Cifra 2 · sufijo', 'amuma' ), 'text' ),
				'stat2_label'  => array( __( 'Cifra 2 · texto', 'amuma' ), 'text' ),
				'stat3_num'    => array( __( 'Cifra 3 · número', 'amuma' ), 'text' ),
				'stat3_suffix' => array( __( 'Cifra 3 · sufijo', 'amuma' ), 'text' ),
				'stat3_label'  => array( __( 'Cifra 3 · texto', 'amuma' ), 'text' ),
			),
		),
		'amuma_services' => array(
			'title'  => __( 'Portada · Cómo te ayudamos', 'amuma' ),
			'fields' => array(
				'services_title' => array( __( 'Título', 'amuma' ), 'text' ),
			),
		),
		'amuma_cta'      => array(
			'title'  => __( 'Portada · Colabora', 'amuma' ),
			'fields' => array(
				'cta_title'  => array( __( 'Título', 'amuma' ), 'text' ),
				'cta1_title' => array( __( 'Opción 1 · título', 'amuma' ), 'text' ),
				'cta1_text'  => array( __( 'Opción 1 · texto', 'amuma' ), 'text' ),
				'cta1_url'   => array( __( 'Opción 1 · enlace', 'amuma' ), 'url' ),
				'cta2_title' => array( __( 'Opción 2 · título', 'amuma' ), 'text' ),
				'cta2_text'  => array( __( 'Opción 2 · texto', 'amuma' ), 'text' ),
				'cta2_url'   => array( __( 'Opción 2 · enlace', 'amuma' ), 'url' ),
				'cta3_title' => array( __( 'Opción 3 · título', 'amuma' ), 'text' ),
				'cta3_text'  => array( __( 'Opción 3 · texto', 'amuma' ), 'text' ),
				'cta3_url'   => array( __( 'Opción 3 · enlace', 'amuma' ), 'url' ),
			),
		),
		'amuma_contact'  => array(
			'title'  => __( 'Contacto y redes', 'amuma' ),
			'fields' => array(
				'address'   => array( __( 'Dirección', 'amuma' ), 'textarea' ),
				'phone1'    => array( __( 'Teléfono 1', 'amuma' ), 'text' ),
				'phone2'    => array( __( 'Teléfono 2', 'amuma' ), 'text' ),
				'email'     => array( __( 'Email', 'amuma' ), 'email' ),
				'hours'     => array( __( 'Horario', 'amuma' ), 'text' ),
				'maps_url'  => array( __( 'Enlace a Google Maps', 'amuma' ), 'url' ),
				'facebook'  => array( __( 'Facebook (URL)', 'amuma' ), 'url' ),
				'instagram' => array( __( 'Instagram (URL)', 'amuma' ), 'url' ),
				'x'         => array( __( 'X / Twitter (URL)', 'amuma' ), 'url' ),
				'youtube'   => array( __( 'YouTube (URL)', 'amuma' ), 'url' ),
			),
		),
	);

	foreach ( $sections as $section_id => $section ) {
		$wp_customize->add_section(
			$section_id,
			array(
				'title' => $section['title'],
				'panel' => 'amuma',
			)
		);
		foreach ( $section['fields'] as $key => $field ) {
			list( $label, $type ) = $field;
			$sanitize             = 'sanitize_text_field';
			if ( 'textarea' === $type ) {
				$sanitize = 'sanitize_textarea_field';
			} elseif ( 'url' === $type ) {
				$sanitize = 'esc_url_raw';
			} elseif ( 'email' === $type ) {
				$sanitize = 'sanitize_email';
			}
			$wp_customize->add_setting(
				'amuma_' . $key,
				array(
					'default'           => isset( $d[ $key ] ) ? $d[ $key ] : '',
					'sanitize_callback' => $sanitize,
				)
			);
			$wp_customize->add_control(
				'amuma_' . $key,
				array(
					'label'   => $label,
					'section' => $section_id,
					'type'    => 'url' === $type ? 'text' : $type,
				)
			);
		}
	}
}
add_action( 'customize_register', 'amuma_customize_register' );
