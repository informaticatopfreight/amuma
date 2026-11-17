<?php
/**
 * Página "Hazte socio/a" (slug: hazte-socio).
 * El contenido está en inc/content.php.
 *
 * @package amuma
 */

get_header();
$c     = amuma_content( 'socio' );
$ficha = amuma_upload_url( $c['ficha'] );

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'kicker' => __( 'Hazte socia o socio', 'amuma' ),
		'title'  => __( 'Súmate al lazo', 'amuma' ),
		'intro'  => __( 'Formar parte de AMUMA es apoyar a quienes están pasando por un cáncer de mama o ginecológico, y tener a tu lado a quienes ya lo han vivido.', 'amuma' ),
		'image'  => $c['img'],
	)
);
?>

<section class="section section--tight">
	<div class="container why">
		<div>
			<p class="kicker" data-fade><?php esc_html_e( '¿Por qué hacerte socia?', 'amuma' ); ?></p>
			<h2 class="section__title section__title--sm" data-split><?php esc_html_e( 'Porque juntas somos más fuertes', 'amuma' ); ?></h2>
		</div>
		<ul class="checklist">
			<?php foreach ( $c['motivos'] as $amuma_m ) : ?>
				<li data-reveal><?php echo amuma_icon( 'check' ); // phpcs:ignore ?><span><?php echo esc_html( $amuma_m ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="section fee">
	<div class="container fee__grid">
		<div class="fee__card" data-reveal>
			<span class="fee__label"><?php esc_html_e( 'Cuota de socia/o', 'amuma' ); ?></span>
			<span class="fee__amount"><span data-count="<?php echo esc_attr( $c['cuota'] ); ?>"><?php echo esc_html( $c['cuota'] ); ?></span><small>€/mes</small></span>
			<p><?php echo esc_html( $c['pago'] ); ?></p>
		</div>
		<div class="accounts" data-reveal>
			<?php foreach ( $c['cuentas'] as $amuma_a ) : ?>
				<div class="account">
					<span class="account__bank"><?php echo esc_html( $amuma_a[0] ); ?></span>
					<code class="account__iban"><?php echo esc_html( $amuma_a[1] ); ?></code>
					<button class="account__copy" type="button" data-copy="<?php echo esc_attr( str_replace( ' ', '', $amuma_a[1] ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s banco */ __( 'Copiar IBAN de %s', 'amuma' ), $amuma_a[0] ) ); ?>"><?php echo amuma_icon( 'copy' ); // phpcs:ignore ?></button>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--tight" id="donativos">
	<div class="container donate" data-reveal>
		<div class="donate__ribbon" aria-hidden="true"><?php echo amuma_ribbon_mini(); // phpcs:ignore ?></div>
		<div>
			<h2 class="donate__title"><?php esc_html_e( 'Haz un donativo', 'amuma' ); ?></h2>
			<p><?php echo esc_html( $c['donativo_texto'] ); ?></p>
		</div>
		<div class="account account--light">
			<span class="account__bank"><?php esc_html_e( 'Cuenta para donativos', 'amuma' ); ?></span>
			<code class="account__iban"><?php echo esc_html( $c['donativo_cuenta'] ); ?></code>
			<button class="account__copy" type="button" data-copy="<?php echo esc_attr( str_replace( ' ', '', $c['donativo_cuenta'] ) ); ?>" aria-label="<?php esc_attr_e( 'Copiar IBAN para donativos', 'amuma' ); ?>"><?php echo amuma_icon( 'copy' ); // phpcs:ignore ?></button>
		</div>
	</div>
</section>

<section class="section signup" id="alta">
	<div class="container signup__grid">
		<div>
			<p class="kicker" data-fade><?php esc_html_e( 'Alta', 'amuma' ); ?></p>
			<h2 class="section__title section__title--sm" data-split><?php esc_html_e( '¿Cómo me hago socia?', 'amuma' ); ?></h2>
			<p class="section__lead" data-fade>
				<?php
				printf(
					/* translators: %s: email */
					esc_html__( 'Rellena el formulario o, si lo prefieres, descarga la ficha de inscripción y envíanosla rellenada y firmada a %s. También puedes traerla a la sede.', 'amuma' ),
					'<a href="mailto:' . esc_attr( antispambot( amuma_opt( 'email' ) ) ) . '">' . esc_html( antispambot( amuma_opt( 'email' ) ) ) . '</a>'
				);
				?>
			</p>
			<?php if ( $ficha ) : ?>
				<a class="btn btn--ghost" data-fade href="<?php echo esc_url( $ficha ); ?>" download><span><?php esc_html_e( 'Descargar ficha de inscripción', 'amuma' ); ?></span><?php echo amuma_icon( 'download' ); // phpcs:ignore ?></a>
			<?php endif; ?>
		</div>
		<div class="signup__form" data-reveal><?php echo amuma_form( 'socio' ); // phpcs:ignore ?></div>
	</div>
</section>

<?php
get_footer();
