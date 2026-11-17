<?php
/**
 * Tema AMUMA — funciones principales.
 *
 * @package amuma
 */

defined( 'ABSPATH' ) || exit;

define( 'AMUMA_VERSION', '1.0.0' );
define( 'AMUMA_DIR', get_template_directory() );
define( 'AMUMA_URI', get_template_directory_uri() );

require AMUMA_DIR . '/inc/customizer.php';
require AMUMA_DIR . '/inc/template-tags.php';

/**
 * Configuración del tema.
 */
function amuma_setup() {
	load_theme_textdomain( 'amuma', AMUMA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 360,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_editor_style( 'assets/css/editor.css' );

	// Paleta de colores en el editor, para que lo que escriban encaje con la web.
	add_theme_support(
		'editor-color-palette',
		array(
			array( 'name' => __( 'Rosa AMUMA', 'amuma' ), 'slug' => 'rosa', 'color' => '#e5267b' ),
			array( 'name' => __( 'Rosa suave', 'amuma' ), 'slug' => 'rosa-suave', 'color' => '#fde3ee' ),
			array( 'name' => __( 'Ciruela', 'amuma' ), 'slug' => 'ciruela', 'color' => '#2b0f22' ),
			array( 'name' => __( 'Crema', 'amuma' ), 'slug' => 'crema', 'color' => '#fff8f5' ),
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menú principal', 'amuma' ),
			'footer'  => __( 'Menú del pie', 'amuma' ),
		)
	);

	add_image_size( 'amuma-card', 900, 675, true );
	add_image_size( 'amuma-wide', 1600, 900, true );
	add_image_size( 'amuma-hero', 2200, 1240, false );
}
add_action( 'after_setup_theme', 'amuma_setup' );

/**
 * Al activar el tema: crea las categorías "Noticias" y "Actividades" si no existen.
 */
function amuma_on_activation() {
	foreach ( array( 'noticias' => 'Noticias', 'actividades' => 'Actividades' ) as $slug => $name ) {
		if ( ! term_exists( $slug, 'category' ) ) {
			wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
		}
	}

	// Si la web muestra "las últimas entradas" en portada, se crea una portada fija
	// y la página "Actualidad" para el listado de noticias (Ajustes → Lectura).
	if ( 'posts' === get_option( 'show_on_front' ) ) {
		$home = wp_insert_post(
			array(
				'post_title'  => 'Inicio',
				'post_type'   => 'page',
				'post_status' => 'publish',
			)
		);
		$news = get_page_by_path( 'actualidad' );
		$news = $news ? $news->ID : wp_insert_post(
			array(
				'post_title'  => 'Actualidad',
				'post_name'   => 'actualidad',
				'post_type'   => 'page',
				'post_status' => 'publish',
			)
		);
		if ( $home && ! is_wp_error( $home ) && $news && ! is_wp_error( $news ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home );
			update_option( 'page_for_posts', $news );
		}
	}
}
add_action( 'after_switch_theme', 'amuma_on_activation' );

/**
 * CSS y JS.
 */
function amuma_assets() {
	$uri = AMUMA_URI . '/assets';
	$ver = AMUMA_VERSION;

	wp_enqueue_style( 'amuma-lenis', $uri . '/vendor/lenis.css', array(), '1.3.27' );
	wp_enqueue_style( 'amuma', $uri . '/css/main.css', array(), $ver );

	wp_enqueue_script( 'gsap', $uri . '/vendor/gsap.min.js', array(), '3.15.1', true );
	wp_enqueue_script( 'gsap-scrolltrigger', $uri . '/vendor/ScrollTrigger.min.js', array( 'gsap' ), '3.15.1', true );
	wp_enqueue_script( 'gsap-splittext', $uri . '/vendor/SplitText.min.js', array( 'gsap' ), '3.15.1', true );
	wp_enqueue_script( 'lenis', $uri . '/vendor/lenis.min.js', array(), '1.3.27', true );
	wp_enqueue_script( 'amuma', $uri . '/js/main.js', array( 'gsap', 'gsap-scrolltrigger', 'gsap-splittext', 'lenis' ), $ver, true );

	wp_localize_script(
		'amuma',
		'AMUMA',
		array(
			'i18n' => array(
				'close'   => __( 'Cerrar', 'amuma' ),
				'prev'    => __( 'Anterior', 'amuma' ),
				'next'    => __( 'Siguiente', 'amuma' ),
				'copied'  => __( '¡Enlace copiado!', 'amuma' ),
				'loading' => __( 'Cargando…', 'amuma' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'amuma_assets' );

/**
 * Precarga de fuentes (están alojadas en el tema: sin Google Fonts, sin problemas de RGPD).
 */
function amuma_preload_fonts() {
	foreach ( array( 'fraunces-latin-wght-normal.woff2', 'dm-sans-latin-wght-normal.woff2' ) as $font ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( AMUMA_URI . '/assets/fonts/' . $font ) );
	}
	// Marca que hay JS lo antes posible, para que las animaciones no "parpadeen".
	// Si en 3 s el JS no ha arrancado (error, bloqueador…), se muestra todo sin animación.
	echo "<script>document.documentElement.classList.add('js');setTimeout(function(){if(!window.AMUMA_READY)document.documentElement.classList.add('anim-off')},3000);</script>\n";
}
add_action( 'wp_head', 'amuma_preload_fonts', 1 );

/**
 * Fuera los emojis de WordPress (peso innecesario).
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Extracto un poco más corto y con puntos suspensivos.
 */
add_filter( 'excerpt_length', fn() => 24 );
add_filter( 'excerpt_more', fn() => '…' );

/**
 * Las páginas de Actualidad muestran 9 entradas (1 destacada + rejilla).
 */
function amuma_posts_per_page( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ( $query->is_home() || $query->is_category() || $query->is_tag() || $query->is_date() ) ) {
		$query->set( 'posts_per_page', 10 );
	}
}
add_action( 'pre_get_posts', 'amuma_posts_per_page' );

/**
 * Clases del body.
 */
function amuma_body_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'is-home';
	}
	return $classes;
}
add_filter( 'body_class', 'amuma_body_class' );

/**
 * Las galerías de WordPress enlazan a la imagen grande para poder usar el visor (lightbox) del tema.
 */
add_filter(
	'shortcode_atts_gallery',
	function ( $out ) {
		$out['link'] = 'file';
		$out['size'] = 'large';
		return $out;
	}
);

/**
 * Títulos de archivo sin el prefijo "Categoría:".
 */
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
