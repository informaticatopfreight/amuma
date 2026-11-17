<?php
/**
 * Formularios del tema: contacto y alta de socia/o.
 * Envían un email a la dirección de Personalizar → AMUMA → Contacto y redes.
 * Protección: nonce, campo trampa (honeypot), tiempo mínimo de relleno y límite de envíos por IP.
 *
 * Recomendado en el servidor: un plugin SMTP (p. ej. WP Mail SMTP) para que los correos no acaben en spam.
 *
 * @package amuma
 */

defined( 'ABSPATH' ) || exit;

/**
 * Campos de cada formulario: clave => [etiqueta, tipo, obligatorio, ancho (full|half)].
 */
function amuma_form_fields( $type ) {
	if ( 'socio' === $type ) {
		return array(
			'nombre'    => array( __( 'Nombre', 'amuma' ), 'text', true, 'half' ),
			'apellidos' => array( __( 'Apellidos', 'amuma' ), 'text', true, 'half' ),
			'email'     => array( __( 'Email', 'amuma' ), 'email', false, 'half' ),
			'telefono'  => array( __( 'Teléfono', 'amuma' ), 'tel', true, 'half' ),
			'nif'       => array( __( 'NIF', 'amuma' ), 'text', false, 'half' ),
			'nacimiento' => array( __( 'Fecha de nacimiento', 'amuma' ), 'date', true, 'half' ),
			'direccion' => array( __( 'Dirección', 'amuma' ), 'text', true, 'full' ),
			'cp'        => array( __( 'Código postal', 'amuma' ), 'text', false, 'half' ),
			'localidad' => array( __( 'Localidad', 'amuma' ), 'text', false, 'half' ),
		);
	}
	return array(
		'nombre'   => array( __( 'Nombre', 'amuma' ), 'text', true, 'half' ),
		'telefono' => array( __( 'Teléfono', 'amuma' ), 'tel', false, 'half' ),
		'email'    => array( __( 'Email', 'amuma' ), 'email', true, 'full' ),
		'mensaje'  => array( __( 'Mensaje', 'amuma' ), 'textarea', true, 'full' ),
	);
}

/**
 * Pinta un formulario.
 */
function amuma_form( $type = 'contacto' ) {
	$type   = 'socio' === $type ? 'socio' : 'contacto';
	$status = isset( $_GET['amuma_form'] ) ? sanitize_key( wp_unslash( $_GET['amuma_form'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$which  = isset( $_GET['f'] ) ? sanitize_key( wp_unslash( $_GET['f'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$privacy = get_privacy_policy_url();
	if ( ! $privacy ) {
		$p       = get_page_by_path( 'politica-privacidad' );
		$privacy = $p ? get_permalink( $p ) : '';
	}
	ob_start();
	?>
	<form class="form" id="formulario-<?php echo esc_attr( $type ); ?>" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-form>
		<?php if ( $which === $type && 'ok' === $status ) : ?>
			<div class="form__notice form__notice--ok" role="status"><?php echo amuma_icon( 'heart' ); // phpcs:ignore ?> <?php echo 'socio' === $type ? esc_html__( '¡Gracias! Hemos recibido tu solicitud. Te llamaremos para completar el alta.', 'amuma' ) : esc_html__( '¡Gracias! Hemos recibido tu mensaje y te responderemos lo antes posible.', 'amuma' ); ?></div>
		<?php elseif ( $which === $type && 'error' === $status ) : ?>
			<div class="form__notice form__notice--error" role="alert"><?php esc_html_e( 'No se ha podido enviar. Revisa los campos obligatorios o llámanos por teléfono.', 'amuma' ); ?></div>
		<?php endif; ?>

		<input type="hidden" name="action" value="amuma_form">
		<input type="hidden" name="form_type" value="<?php echo esc_attr( $type ); ?>">
		<input type="hidden" name="form_time" value="<?php echo esc_attr( time() ); ?>">
		<input type="hidden" name="redirect" value="<?php echo esc_url( get_permalink() ); ?>">
		<?php wp_nonce_field( 'amuma_form_' . $type, 'amuma_nonce' ); ?>
		<div class="form__trap" aria-hidden="true"><label>Web <input type="text" name="web" tabindex="-1" autocomplete="off"></label></div>

		<div class="form__grid">
			<?php foreach ( amuma_form_fields( $type ) as $key => $f ) : ?>
				<?php list( $label, $input, $required, $width ) = $f; ?>
				<?php $id = 'f-' . $type . '-' . $key; ?>
				<div class="form__field form__field--<?php echo esc_attr( $width ); ?>">
					<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?><?php echo $required ? ' <span aria-hidden="true">*</span>' : ''; ?></label>
					<?php if ( 'textarea' === $input ) : ?>
						<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $key ); ?>" rows="5" <?php echo $required ? 'required' : ''; ?>></textarea>
					<?php else : ?>
						<input id="<?php echo esc_attr( $id ); ?>" type="<?php echo esc_attr( $input ); ?>" name="<?php echo esc_attr( $key ); ?>" <?php echo $required ? 'required' : ''; ?> autocomplete="<?php echo esc_attr( array( 'nombre' => 'given-name', 'apellidos' => 'family-name', 'email' => 'email', 'telefono' => 'tel', 'direccion' => 'street-address', 'cp' => 'postal-code', 'localidad' => 'address-level2' )[ $key ] ?? 'off' ); ?>">
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<label class="form__check">
			<input type="checkbox" name="privacidad" value="1" required>
			<span>
				<?php
				printf(
					/* translators: %s: enlace a la política de privacidad */
					esc_html__( 'He leído y acepto la %s. *', 'amuma' ),
					$privacy ? '<a href="' . esc_url( $privacy ) . '" target="_blank">' . esc_html__( 'política de privacidad', 'amuma' ) . '</a>' : esc_html__( 'política de privacidad', 'amuma' )
				);
				?>
			</span>
		</label>
		<p class="form__legal"><?php esc_html_e( 'Responsable: AMUMA. Finalidad: atender tu solicitud. No cederemos tus datos a terceros. Puedes ejercer tus derechos escribiendo a', 'amuma' ); ?> <?php echo esc_html( antispambot( amuma_opt( 'email' ) ) ); ?>.</p>

		<button class="btn btn--pink" type="submit"><span><?php echo 'socio' === $type ? esc_html__( 'Enviar solicitud', 'amuma' ) : esc_html__( 'Enviar mensaje', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></button>
	</form>
	<?php
	return ob_get_clean();
}

/**
 * Procesa el envío.
 */
function amuma_form_submit() {
	$type     = isset( $_POST['form_type'] ) && 'socio' === $_POST['form_type'] ? 'socio' : 'contacto'; // phpcs:ignore WordPress.Security.NonceVerification
	$redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : home_url( '/' ); // phpcs:ignore WordPress.Security.NonceVerification
	$redirect = wp_validate_redirect( $redirect, home_url( '/' ) );
	$back     = function ( $status ) use ( $redirect, $type ) {
		wp_safe_redirect( add_query_arg( array( 'amuma_form' => $status, 'f' => $type ), $redirect ) . '#formulario-' . $type );
		exit;
	};

	if ( ! isset( $_POST['amuma_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['amuma_nonce'] ) ), 'amuma_form_' . $type ) ) {
		$back( 'error' );
	}
	// Robots: campo trampa relleno o envío en menos de 3 segundos → se finge éxito y no se envía.
	$elapsed = time() - ( isset( $_POST['form_time'] ) ? absint( $_POST['form_time'] ) : 0 );
	if ( ! empty( $_POST['web'] ) || $elapsed < 3 ) {
		$back( 'ok' );
	}
	// Máximo 5 envíos por hora desde la misma IP.
	$ip_key = 'amuma_form_' . md5( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' );
	$sent   = (int) get_transient( $ip_key );
	if ( $sent >= 5 ) {
		$back( 'error' );
	}

	if ( empty( $_POST['privacidad'] ) ) {
		$back( 'error' );
	}

	$lines = array();
	$reply = '';
	foreach ( amuma_form_fields( $type ) as $key => $f ) {
		list( $label, $input, $required ) = $f;
		$raw   = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$value = 'textarea' === $input ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
		if ( 'email' === $input && $value && ! is_email( $value ) ) {
			$back( 'error' );
		}
		if ( $required && '' === trim( $value ) ) {
			$back( 'error' );
		}
		if ( 'email' === $input ) {
			$reply = $value;
		}
		$lines[] = $label . ': ' . ( '' === $value ? '—' : $value );
	}

	$to      = amuma_opt( 'email' ) ? amuma_opt( 'email' ) : get_option( 'admin_email' );
	$subject = 'socio' === $type ? '[Web AMUMA] Nueva solicitud de socia/o' : '[Web AMUMA] Nuevo mensaje de contacto';
	$body    = implode( "\n", $lines ) . "\n\n— " . __( 'Enviado desde', 'amuma' ) . ' ' . $redirect . "\n" . __( 'Aceptó la política de privacidad', 'amuma' ) . ': ' . wp_date( 'd/m/Y H:i' );
	$headers = $reply ? array( 'Reply-To: ' . $reply ) : array();

	$ok = wp_mail( $to, $subject, $body, $headers );
	set_transient( $ip_key, $sent + 1, HOUR_IN_SECONDS );
	$back( $ok ? 'ok' : 'error' );
}
add_action( 'admin_post_amuma_form', 'amuma_form_submit' );
add_action( 'admin_post_nopriv_amuma_form', 'amuma_form_submit' );

/**
 * Shortcodes por si quieren poner un formulario en cualquier página: [amuma_contacto] y [amuma_socio].
 */
add_shortcode( 'amuma_contacto', fn() => amuma_form( 'contacto' ) );
add_shortcode( 'amuma_socio', fn() => amuma_form( 'socio' ) );
