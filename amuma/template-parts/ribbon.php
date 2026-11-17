<?php
/**
 * Lazo rosa en SVG. Uso: get_template_part( 'template-parts/ribbon', null, [ 'class' => 'x', 'id' => 'y' ] ).
 * Se dibuja con dos tramos (trasero y delantero) para que las animaciones puedan "trazarlo".
 */
$amuma_uid   = isset( $args['id'] ) ? sanitize_html_class( $args['id'] ) : 'rb' . wp_rand( 100, 99999 );
$amuma_class = isset( $args['class'] ) ? $args['class'] : '';
?>
<svg class="ribbon <?php echo esc_attr( $amuma_class ); ?>" viewBox="0 0 200 300" aria-hidden="true" focusable="false">
	<defs>
		<linearGradient id="<?php echo esc_attr( $amuma_uid ); ?>-g" gradientUnits="userSpaceOnUse" x1="40" y1="20" x2="170" y2="290">
			<stop offset="0" stop-color="#ff8dbd"/>
			<stop offset=".5" stop-color="#e83e8c"/>
			<stop offset="1" stop-color="#b01564"/>
		</linearGradient>
		<filter id="<?php echo esc_attr( $amuma_uid ); ?>-s" x="-40%" y="-40%" width="180%" height="180%">
			<feGaussianBlur stdDeviation="3.2"/>
		</filter>
	</defs>
	<path class="ribbon__back" d="M100 26 C 70 26, 56 62, 66 104 C 76 150, 112 196, 150 284" fill="none" stroke="url(#<?php echo esc_attr( $amuma_uid ); ?>-g)" stroke-width="30"/>
	<path class="ribbon__shadow" d="M131 118 C 122 152, 104 180, 86 210" fill="none" stroke="#6d0b3c" stroke-opacity=".35" stroke-width="30" filter="url(#<?php echo esc_attr( $amuma_uid ); ?>-s)" transform="translate(4 3)"/>
	<path class="ribbon__front" d="M96 26.2 C 98 26, 99 26, 100 26 C 130 26, 144 62, 134 104 C 124 150, 88 196, 50 284" fill="none" stroke="url(#<?php echo esc_attr( $amuma_uid ); ?>-g)" stroke-width="30"/>
	<path class="ribbon__shine" d="M102 16 C 128 18, 140 50, 136 88" fill="none" stroke="#fff" stroke-opacity=".45" stroke-width="3" stroke-linecap="round"/>
	<path class="ribbon__shine" d="M118 152 C 104 190, 84 230, 62 274" fill="none" stroke="#fff" stroke-opacity=".3" stroke-width="3" stroke-linecap="round"/>
</svg>
