<?php
/**
 * Banda final "Hazte socia".
 *
 * @package amuma
 */

$amuma_socio = amuma_content( 'socio' );
?>
<section class="cta-band">
	<div class="container cta-band__inner">
		<div class="cta-band__ribbon" aria-hidden="true"><?php echo amuma_ribbon_mini(); // phpcs:ignore ?></div>
		<div>
			<h2 class="cta-band__title" data-split><?php esc_html_e( 'Juntas somos más fuertes', 'amuma' ); ?></h2>
			<p class="cta-band__text" data-fade>
				<?php
				/* translators: %s: cuota mensual */
				printf( esc_html__( 'Hazte socia o socio por %s € al mes y accede a todos nuestros servicios, o colabora con un donativo.', 'amuma' ), esc_html( $amuma_socio['cuota'] ) );
				?>
			</p>
		</div>
		<div class="cta-band__actions" data-fade>
			<a class="btn btn--light" data-magnetic href="<?php echo esc_url( amuma_page_url( 'hazte-socio', '/#colabora' ) ); ?>"><span><?php esc_html_e( 'Hazte socia', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></a>
			<a class="btn btn--outline-light" data-magnetic href="<?php echo esc_url( amuma_page_url( 'contacto', '/#contacto' ) ); ?>"><span><?php esc_html_e( 'Contacta', 'amuma' ); ?></span></a>
		</div>
	</div>
</section>
