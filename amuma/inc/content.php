<?php
/**
 * Contenido fijo de las páginas (Asociación, Servicios, Hazte socio/a, Actividades, Contacto).
 * Textos tomados de amuma.org. Para cambiar algo, se edita aquí.
 *
 * Las imágenes se indican como ruta dentro de wp-content/uploads (las que ya están en la web).
 * Si una imagen no existe, simplemente no se muestra.
 *
 * @package amuma
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL de un archivo de la carpeta de subidas (uploads), o '' si no existe.
 */
function amuma_upload_url( $relative ) {
	if ( ! $relative ) {
		return '';
	}
	// Imágenes incluidas en el propio tema (assets/img).
	if ( 0 === strpos( $relative, 'theme/' ) ) {
		return AMUMA_URI . '/assets/img/' . substr( $relative, 6 );
	}
	$up   = wp_get_upload_dir();
	$base = trailingslashit( $up['basedir'] );
	if ( ! file_exists( $base . $relative ) ) {
		// Nombres con tildes: en el servidor pueden estar guardados como "i + acento" (Unicode NFD).
		$found = '';
		if ( class_exists( 'Normalizer' ) ) {
			foreach ( array( Normalizer::FORM_D, Normalizer::FORM_C ) as $form ) {
				$alt = Normalizer::normalize( $relative, $form );
				if ( $alt && file_exists( $base . $alt ) ) {
					$found = $alt;
					break;
				}
			}
		}
		if ( ! $found ) {
			$decomposed = strtr( $relative, array( 'á' => "a\u{301}", 'é' => "e\u{301}", 'í' => "i\u{301}", 'ó' => "o\u{301}", 'ú' => "u\u{301}", 'ñ' => "n\u{303}" ) );
			if ( file_exists( $base . $decomposed ) ) {
				$found = $decomposed;
			}
		}
		if ( ! $found ) {
			return '';
		}
		$relative = $found;
	}
	return trailingslashit( $up['baseurl'] ) . str_replace( '%2F', '/', rawurlencode( $relative ) );
}

/**
 * Todo el contenido.
 */
function amuma_content( $section = null ) {
	$c = array(

		/* ---------------------------------------------------------------- */
		'asociacion'  => array(
			'intro'         => 'Somos una asociación sin ánimo de lucro fundada en 1997 como un grupo de ayuda socio-sanitaria para dar apoyo a las personas afectadas de cáncer de mama, así como a sus familiares y cuidadores. Con los años ampliamos nuestros estatutos para atender también el cáncer ginecológico.',
			'ambito'        => 'El ámbito de AMUMA es regional, aunque hoy nuestro campo de actuación se centra en Ciudad Real y los pueblos de alrededor.',
			'quienes'       => 'Somos un grupo, mayoritariamente de mujeres, que decidimos compartir, informar, aprender a vivir con la enfermedad y sus secuelas, reivindicar nuestros derechos y sensibilizar a la sociedad.',
			'equipo'        => 'El equipo de AMUMA lo forman la junta directiva, profesionales, voluntariado y entidades colaboradoras que trabajan juntas para apoyar a las personas afectadas de cáncer de mama y ginecológico y dar visibilidad a esta enfermedad.',
			'junta_nota'    => 'Dos de los miembros de la Junta Directiva han pasado también por un cáncer de mama: han vivido la enfermedad en primera persona, conocen las necesidades de las socias y socios y te van a entender a la perfección. Todas trabajan de forma altruista para ayudarte y defender los intereses del colectivo.',
			'junta'         => array(
				array( 'Sonia Marchán', 'Presidenta', '2026/08/WhatsApp-Image-2026-08-18-at-13.27.07-3.jpeg' ),
				array( 'Ascensión Martín', 'Secretaria', '2026/08/SCR-20260818-psjm.png' ),
				array( 'M.ª Mar García', 'Tesorera', '2021/03/IMG-20210303-WA0004-e1787068151283.jpg' ),
				array( 'Teresa Roldán Mora', 'Vocal de Actividades', '2023/04/WhatsApp-Image-2023-03-27-at-13.17.33.jpeg' ),
				array( 'M.ª Carmen Sánchez-Cambronero Martín-Consuegra', 'Vocal', '2026/08/WhatsApp-Image-2026-08-18-at-13.27.07-4.jpeg' ),
			),
			'profesionales' => array(
				array( 'Rosa García-Madrid', 'Coordinadora', '2021/03/IMG-20210303-WA0003.jpg' ),
				array( 'Ángeles García-Moreno', 'Administrativa y contable', '2021/03/unnamed.jpg' ),
			),
			'voluntariado'  => 'Gracias a la participación de nuestras socias, socios y voluntariado podemos llevar a cabo actividades como el mercadillo solidario o la cuestación. Además, son un apoyo fundamental para las personas recién diagnosticadas que llegan por primera vez a la asociación.',
			'voluntariado_img' => '2021/03/10.jpg',
			'colaboradores' => 'Gracias a la colaboración de entidades públicas, administraciones y empresas y comercios de Ciudad Real y provincia podemos llevar a cabo muchas de nuestras actividades. ¡Muchas gracias de corazón a todos!',
			'mision'        => array(
				'Dar apoyo emocional, orientación y asesoramiento a las personas diagnosticadas de cáncer de mama o ginecológico, y a sus familiares o cuidadores.',
				'Favorecer una estrecha solidaridad entre las personas afectadas o en tratamiento.',
				'Ser punto de encuentro para quienes quieran estar en contacto con otras personas en su misma situación.',
				'Favorecer la inclusión social de las personas afectadas, mejorando en todo lo posible su calidad de vida.',
				'Informar y sensibilizar a la sociedad.',
				'Apoyar y promover los programas de detección precoz.',
				'Apoyar la investigación.',
				'Reivindicar ante los organismos públicos las necesidades detectadas en el colectivo.',
				'Actuar como interlocutoras con las distintas administraciones.',
			),
			'hito'          => 'AMUMA fue la primera asociación que solicitó en España la celebración del 19 de octubre como Día del Cáncer de Mama, como ya se hacía en el resto del mundo.',
		),

		/* ---------------------------------------------------------------- */
		'servicios'   => array(
			'intro'     => 'Ofrecemos servicios muy variados para hacer más llevadero el paso por la enfermedad y para mejorar la calidad de vida una vez terminados los tratamientos.',
			'lista'     => array(
				array(
					'slug'  => 'psicologia',
					'icon'  => 'heart',
					'title' => 'Psicología',
					'short' => 'Atención psicológica individual para personas afectadas, familiares y cuidadores.',
					'text'  => array(
						'Ofrecemos atención psicológica individual a las personas afectadas de cáncer de mama o ginecológico, a sus familiares y a sus cuidadores.',
						'También hay sesiones grupales, en las que se pueden compartir experiencias con otras personas que están pasando por la misma situación.',
					),
					'img'   => '2021/02/adult-and-child-hands-holding-encephalography-brain-paper-cutout-epilepsy-and-alzheimer-awareness-seizure-disorder-mental-health-concept.jpg',
				),
				array(
					'slug'  => 'fisioterapia',
					'icon'  => 'body',
					'title' => 'Fisioterapia',
					'short' => 'Drenaje linfático manual y presoterapia para tratar el linfedema del brazo.',
					'text'  => array(
						'Ofrecemos sesiones de Drenaje Linfático Manual (DLM) y presoterapia para tratar el linfedema del brazo. Este masaje lo realiza un fisioterapeuta especializado, que estimula el drenaje de los conductos linfáticos con movimientos especiales.',
						'Tenemos convenios con clínicas de fisioterapia de Daimiel, Manzanares, Villanueva de los Infantes y Puertollano para acercar el servicio a nuestras socias y socios.',
						'También subvencionamos el 50 % de los masajes descontracturantes de espalda y cuello, para tratar las molestias que dejan las operaciones, los tratamientos o la tensión acumulada.',
					),
					'img'   => '2021/02/pressotherapy-cellulite-reduction-suit-lymphatic-drainage-weight-loss-modern-technology-treatment-for-health-body-and-beauty-improvement.jpg',
				),
				array(
					'slug'  => 'nutricion',
					'icon'  => 'leaf',
					'title' => 'Nutrición',
					'short' => 'Consulta personalizada con una especialista en nutrición.',
					'text'  => array(
						'Ponemos a disposición de nuestras socias y socios una consulta personalizada de nutrición dirigida por una especialista, para mejorar la alimentación y asegurar un aporte nutricional adecuado.',
					),
					'img'   => '2021/02/diet-concept-with-female-scientist-and-healthy-food.jpg',
				),
				array(
					'slug'  => 'oncoestetica',
					'icon'  => 'sparkle',
					'title' => 'Oncoestética',
					'short' => 'Cuidado de la piel durante la quimioterapia, colocación de pañuelo o turbante.',
					'text'  => array(
						'Cuidado de la piel durante la quimioterapia, colocación del pañuelo o del turbante y otros consejos para sentirte bien durante el tratamiento.',
					),
					'img'   => '2021/02/oncoestetica-805x503-1.jpg',
				),
				array(
					'slug'  => 'podologia',
					'icon'  => 'foot',
					'title' => 'Podología',
					'short' => 'Tratamiento de los problemas de uñas que puede causar la quimioterapia.',
					'text'  => array(
						'Hemos incorporado un servicio de podología para tratar los problemas de las uñas que pueden aparecer como consecuencia de la quimioterapia.',
					),
					'img'   => '2021/03/woman-in-podiatry-receiving-pedicure.jpg',
				),
				array(
					'slug'  => 'ayudas',
					'icon'  => 'doc',
					'title' => 'Tramitación de ayudas',
					'short' => 'Ayudas para prótesis, manguitos, pelucas, medicamentos y más.',
					'text'  => array(
						'Tramitamos ayudas para la prótesis mamaria definitiva y el manguito para el linfedema.',
						'Además, tenemos ayudas de acción social para personas desfavorecidas: pelucas, prótesis definitivas, medicamentos, vacuna del VPH y otras necesidades.',
					),
					'img'   => '2021/03/vista-frontal-joven-bella-dama-camisa-gris-trabajando-documentos-computadora-portatil-sentado-dentro-su-oficina-dia-actividad-laboral-edificio_140725-15140.jpg',
				),
			),
			'nuevas'    => array(
				'title' => 'Si acabas de recibir el diagnóstico',
				'text'  => array(
					'Te ofrecemos información y asesoramiento personalizado según lo que necesites.',
					'Contamos con un grupo de acogida formado por socias que han pasado por la enfermedad y que te darán apoyo emocional y consejo.',
				),
				'kit'   => array(
					array( 'Cojín con forma de corazón', 'Ayuda a prevenir el linfedema y alivia la zona tras la operación.', '2021/03/Cojín-2.jpg' ),
					array( 'Pelota ortopédica', 'Para hacer ejercicios con la mano y recuperar movilidad.', 'theme/kit-pelota.jpg' ),
					array( 'Prótesis provisional', 'Para las personas mastectomizadas, mientras llega la definitiva.', 'theme/kit-protesis.jpg' ),
					array( 'Turbante oncológico', 'De regalo, si ya has empezado la quimioterapia.', '2021/03/Turbante-2.jpg' ),
				),
			),
			'otros'     => array(
				array( 'Yoga', 'Reduce la fatiga y mejora la depresión, la ansiedad y los trastornos del sueño. En grupos reducidos, una vez a la semana, con una monitora especializada.', '2021/03/young-sporty-woman-practicing-yoga-in-pink-yoga-suit-at-sunrise-in-the-park.jpg' ),
				array( 'Pilates', 'Ayuda a recuperar la movilidad del brazo y el hombro, reduce el riesgo de linfedema, fortalece el abdomen y mejora el equilibrio, la postura, el descanso y el ánimo. Una vez a la semana, con una monitora especializada.', '2021/03/mujer-joven-haciendo-ejercicio-fitness_144627-30717.jpg' ),
				array( 'Lunes rosas', 'Charlas y talleres, normalmente el último lunes de cada mes: alimentación, memoria, risoterapia, sexualidad y otros temas de interés.', '2021/03/TallerSexualidad2.jpg' ),
				array( 'Taller de manualidades', 'Un grupo que hace manualidades en la sede o en casa: pulseras, llaveros, ganchillo…', '2021/03/mujeres-irreconocibles-mascarilla-practicando-tejido-distancia-social-higienica_213512-2434.jpg' ),
				array( 'Tiro con arco', 'Entrenar la fuerza del brazo, el pecho y el hombro ayuda a prevenir el linfedema o a reducir sus síntomas. Por eso hacemos clases semanales de tiro con arco con el Club de Arqueros de D. Gil de Ciudad Real.', '2022/07/ClubArqueros.jpg' ),
			),
		),

		/* ---------------------------------------------------------------- */
		'socio'       => array(
			'motivos' => array(
				'Si has tenido cáncer de mama o ginecológico, porque juntas somos más fuertes.',
				'Si necesitas nuestros recursos y servicios.',
				'Para colaborar activamente en las actividades que organizamos.',
				'Para colaborar económicamente como socia o socio benefactor.',
				'Por solidaridad con las personas afectadas de cáncer de mama o ginecológico.',
			),
			'cuota'   => '6',
			'pago'    => 'Puedes pagarla en la sede (Ronda de Calatrava 18, local, esquina con C/ Felipe II) o por ingreso o transferencia:',
			'cuentas' => array(
				array( 'Globalcaja', 'ES67 3190 2082 2021 5163 5717' ),
				array( 'Unicaja', 'ES93 2103 0439 6600 3001 3851' ),
				array( 'La Caixa', 'ES60 2100 2191 3602 0024 5369' ),
			),
			'donativo_cuenta' => 'ES67 3190 2082 2021 5163 5717',
			'donativo_texto'  => 'Con muy poco puedes hacer mucho. Todas las aportaciones suman y nos ayudan a seguir con nuestra misión.',
			'ficha'   => '2025/SolicitudSocios.doc',
			'img'     => '2021/02/hands-holding-pink-ribbons-for-breast-cancer-awareness-scaled.jpg',
		),

		/* ---------------------------------------------------------------- */
		'actividades' => array(
			'intro'     => 'Organizamos muchísimas actividades. Algunas ya son todo un clásico en la asociación y otras cambian según la demanda y las circunstancias.',
			'fijas'     => array(
				array( 'Carrera Rosa', 'Una de nuestras citas más queridas: llenamos de rosa las calles de Ciudad Real para correr o caminar contra el cáncer de mama.', '2021/03/CarreraRosa5.jpg' ),
				array( 'Cuestación', 'Mesas solidarias en Ciudad Real y en pueblos de la provincia con motivo del 19 de octubre, Día del Cáncer de Mama.', '2021/03/Cuestaciones-1.jpg' ),
				array( 'Mercadillo solidario', 'Nuestro mercadillo solidario: todo lo que se recauda se destina a la atención de las personas afectadas.', '2022/11/Mercadillo-Solidario.jpeg' ),
				array( 'Lotería de Navidad', 'Llevar lotería de AMUMA en Navidad es otra forma de ayudarnos a seguir.', '' ),
			),
			'variables' => array( 'Conciertos solidarios', 'Charlas informativas', 'Talleres de autoexploración mamaria', 'Iluminación de edificios', 'Alfombra de sal', 'Lunes rosas', 'Convivencias de socias' ),
			'tejiendo'  => array(
				'title'  => 'Tejiendo por la vida',
				'text'   => 'Con motivo del Día Mundial contra el Cáncer de Mama hicimos un gran lazo rosa con cuadrados de punto y ganchillo de 40 × 40 cm, en cualquier tono rosa, tejidos por socias, voluntariado y vecinas. Unimos todos los trozos para dar forma al lazo y lo expusimos en la plaza.',
				'video'  => '2021/06/VideoLazo.mp4',
				'poster' => '2021/06/11_CartelTejiendo-por-la-vida.jpg',
			),
		),

		/* ---------------------------------------------------------------- */
		'contacto'    => array(
			'intro' => 'Si tienes cualquier duda o quieres saber más sobre la asociación, ven a la sede, llámanos, escríbenos un email o rellena el formulario. Estaremos encantadas de atenderte.',
			'lat'   => '38.9894508',
			'lng'   => '-3.9216924',
		),
	);

	if ( $section ) {
		return isset( $c[ $section ] ) ? $c[ $section ] : array();
	}
	return $c;
}
