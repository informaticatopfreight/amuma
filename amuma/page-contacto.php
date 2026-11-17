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
// Mapa de OpenStreetMap: sin cookies ni rastreo (no necesita aviso de cookies).
$bbox = sprintf( '%F,%F,%F,%F', $lng - 0.006, $lat - 0.0035, $lng + 0.006, $lat + 0.0035 );
$map  = 'https://www.openstreetmap.org/export/embed.html?bbox=' . rawurlencode( $bbox ) . '&layer=mapnik&marker=' . rawurlencode( $lat . ',' . $lng );

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
	<iframe title="<?php esc_attr_e( 'Mapa: sede de AMUMA en Ciudad Real', 'amuma' ); ?>" src="<?php echo esc_url( $map ); ?>" loading="lazy"></iframe>
	<a class="map__pin btn btn--pink btn--sm" href="<?php echo esc_url( amuma_opt( 'maps_url' ) ); ?>" target="_blank" rel="noopener"><span><?php esc_html_e( 'Abrir en Google Maps', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow-up' ); // phpcs:ignore ?></a>
</section>

<?php
get_footer();
