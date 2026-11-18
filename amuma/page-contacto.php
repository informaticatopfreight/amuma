<?php
/**
 * Página "Contacto" (slug: contacto).
 *
 * @package amuma
 */

get_header();
$c   = amuma_content( 'contacto' );
$lat = (float) $c['lat'];
$lng = (float) $c['lng'];
// Mapa de Google: solo se carga cuando la persona pulsa "Ver mapa" (sin cookies de Google hasta entonces).
$map = 'https://maps.google.com/maps?q=' . rawurlencode( $lat . ',' . $lng ) . '&z=17&hl=es&output=embed';

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'kicker' => __( 'Contacto', 'amuma' ),
		'title'  => __( 'Estamos aquí', 'amuma' ),
		'intro'  => $c['intro'],
	)
);
?>

<section class="section section--tight">
	<div class="container contact-page">
		<div class="contact__cards contact__cards--stack">
			<a class="contact__card" data-reveal href="<?php echo esc_url( amuma_opt( 'maps_url' ) ); ?>" target="_blank" rel="noopener">
				<?php echo amuma_icon( 'pin' ); // phpcs:ignore ?>
				<span class="contact__label"><?php esc_html_e( 'Sede', 'amuma' ); ?></span>
				<span class="contact__value"><?php echo nl2br( esc_html( amuma_opt( 'address' ) ) ); ?></span>
				<span class="link-arrow"><?php esc_html_e( 'Cómo llegar', 'amuma' ); ?> <?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></span>
			</a>
			<div class="contact__card" data-reveal>
				<?php echo amuma_icon( 'phone' ); // phpcs:ignore ?>
				<span class="contact__label"><?php esc_html_e( 'Teléfonos', 'amuma' ); ?></span>
				<span class="contact__value">
					<?php foreach ( array( 'phone1', 'phone2' ) as $amuma_ph ) : ?>
						<?php if ( amuma_opt( $amuma_ph ) ) : ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', amuma_opt( $amuma_ph ) ) ); ?>"><?php echo esc_html( amuma_opt( $amuma_ph ) ); ?></a><br>
						<?php endif; ?>
					<?php endforeach; ?>
				</span>
			</div>
			<a class="contact__card" data-reveal href="mailto:<?php echo esc_attr( antispambot( amuma_opt( 'email' ) ) ); ?>">
				<?php echo amuma_icon( 'mail' ); // phpcs:ignore ?>
				<span class="contact__label"><?php esc_html_e( 'Email', 'amuma' ); ?></span>
				<span class="contact__value"><?php echo esc_html( antispambot( amuma_opt( 'email' ) ) ); ?></span>
			</a>
			<div class="contact__card" data-reveal>
				<?php echo amuma_icon( 'clock' ); // phpcs:ignore ?>
				<span class="contact__label"><?php esc_html_e( 'Horario', 'amuma' ); ?></span>
				<span class="contact__value"><?php echo esc_html( amuma_opt( 'hours' ) ); ?></span>
			</div>
			<?php echo amuma_social_links(); // phpcs:ignore ?>
		</div>
		<div class="contact-page__form" data-reveal>
			<h2 class="section__title section__title--sm"><?php esc_html_e( 'Escríbenos', 'amuma' ); ?></h2>
			<?php echo amuma_form( 'contacto' ); // phpcs:ignore ?>
		</div>
	</div>
</section>

<section class="map container" data-reveal>
	<div class="map__box" data-map data-src="<?php echo esc_url( $map ); ?>">
		<svg class="map__art" viewBox="0 0 1200 520" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
			<rect width="1200" height="520" class="map__ground"/>
			<path class="map__park" d="M80 330 C 140 280, 260 290, 300 340 C 340 395, 300 470, 210 480 C 120 490, 30 420, 80 330 Z"/>
			<path class="map__park" d="M930 40 C 1010 20, 1120 50, 1130 120 C 1140 190, 1040 210, 980 180 C 910 150, 870 60, 930 40 Z"/>
			<g class="map__streets">
				<path d="M-20 140 C 300 120, 520 170, 760 150 S 1100 110, 1230 130"/>
				<path d="M-20 400 C 260 380, 480 420, 700 395 S 1050 360, 1230 380"/>
				<path d="M180 -20 C 200 150, 170 330, 210 540"/>
				<path d="M470 -20 C 450 160, 500 320, 470 540"/>
				<path d="M840 -20 C 870 140, 820 330, 860 540"/>
				<path d="M1060 -20 C 1040 200, 1090 360, 1060 540"/>
			</g>
			<g class="map__ring">
				<ellipse cx="610" cy="265" rx="330" ry="215"/>
			</g>
			<g class="map__minor">
				<path d="M330 -20 L 350 540"/><path d="M650 -20 L 640 540"/><path d="M-20 270 L 1230 255"/><path d="M960 -20 L 990 540"/>
			</g>
		</svg>
		<div class="map__marker" aria-hidden="true">
			<span class="map__pulse"></span>
			<span class="map__dot"><?php echo amuma_ribbon_mini(); // phpcs:ignore ?></span>
		</div>
		<div class="map__card">
			<span class="contact__label"><?php esc_html_e( 'Sede de AMUMA', 'amuma' ); ?></span>
			<span class="map__address"><?php echo nl2br( esc_html( amuma_opt( 'address' ) ) ); ?></span>
			<div class="map__actions">
				<button class="btn btn--pink btn--sm" type="button" data-map-load><span><?php esc_html_e( 'Ver mapa', 'amuma' ); ?></span><?php echo amuma_icon( 'pin' ); // phpcs:ignore ?></button>
				<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( amuma_opt( 'maps_url' ) ); ?>" target="_blank" rel="noopener"><span><?php esc_html_e( 'Cómo llegar', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow-up' ); // phpcs:ignore ?></a>
			</div>
			<span class="map__note"><?php esc_html_e( 'Al ver el mapa se carga Google Maps.', 'amuma' ); ?></span>
		</div>
	</div>
</section>

<?php
get_footer();
