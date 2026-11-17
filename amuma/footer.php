<?php
/**
 * Pie.
 *
 * @package amuma
 */
?>
</main>

<footer class="site-footer">
	<div class="site-footer__ribbon" aria-hidden="true">
		<?php get_template_part( 'template-parts/ribbon', null, array( 'id' => 'footer-ribbon', 'class' => 'ribbon--footer' ) ); ?>
	</div>
	<div class="container">
		<div class="site-footer__top">
			<p class="site-footer__claim" data-split><?php esc_html_e( 'Estamos aquí para ti.', 'amuma' ); ?></p>
			<a class="btn btn--light" data-magnetic href="mailto:<?php echo esc_attr( antispambot( amuma_opt( 'email' ) ) ); ?>">
				<span><?php esc_html_e( 'Escríbenos', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?>
			</a>
		</div>

		<div class="site-footer__grid">
			<div>
				<h2 class="site-footer__title"><?php esc_html_e( 'Sede', 'amuma' ); ?></h2>
				<p><?php echo nl2br( esc_html( amuma_opt( 'address' ) ) ); ?></p>
			</div>
			<div>
				<h2 class="site-footer__title"><?php esc_html_e( 'Contacto', 'amuma' ); ?></h2>
				<p>
					<?php foreach ( array( 'phone1', 'phone2' ) as $ph ) : ?>
						<?php if ( amuma_opt( $ph ) ) : ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', amuma_opt( $ph ) ) ); ?>"><?php echo esc_html( amuma_opt( $ph ) ); ?></a><br>
						<?php endif; ?>
					<?php endforeach; ?>
					<a href="mailto:<?php echo esc_attr( antispambot( amuma_opt( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( amuma_opt( 'email' ) ) ); ?></a>
				</p>
			</div>
			<div>
				<h2 class="site-footer__title"><?php esc_html_e( 'Navegación', 'amuma' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-menu',
						'fallback_cb'    => 'amuma_menu_fallback',
						'depth'          => 1,
					)
				);
				?>
			</div>
			<?php if ( amuma_social_links() ) : ?>
				<div>
					<h2 class="site-footer__title"><?php esc_html_e( 'Síguenos', 'amuma' ); ?></h2>
					<?php echo amuma_social_links(); // phpcs:ignore ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="site-footer__word" aria-hidden="true">AMUMA</div>

		<div class="site-footer__bottom">
			<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> AMUMA · <?php esc_html_e( 'Asociación de Cáncer de Mama y Ginecológico de Castilla-La Mancha', 'amuma' ); ?></span>
			<nav class="legal" aria-label="<?php esc_attr_e( 'Información legal', 'amuma' ); ?>">
				<?php
				foreach ( array( 'aviso-legal' => __( 'Aviso legal', 'amuma' ), 'politica-privacidad' => __( 'Política de privacidad', 'amuma' ), 'politica-de-cookies' => __( 'Política de cookies', 'amuma' ) ) as $amuma_slug => $amuma_label ) {
					$amuma_pg = get_page_by_path( $amuma_slug );
					if ( $amuma_pg ) {
						printf( '<a href="%s">%s</a>', esc_url( get_permalink( $amuma_pg ) ), esc_html( $amuma_label ) );
					}
				}
				?>
			</nav>
			<a href="#contenido" class="to-top" data-to-top><?php esc_html_e( 'Volver arriba', 'amuma' ); ?> <?php echo amuma_icon( 'arrow-up' ); // phpcs:ignore ?></a>
		</div>
	</div>
</footer>

<div class="cursor-ribbon" aria-hidden="true"></div>
<?php wp_footer(); ?>
</body>
</html>
