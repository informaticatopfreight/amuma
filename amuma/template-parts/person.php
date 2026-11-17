<?php
/**
 * Persona del equipo. $args['p'] = [nombre, cargo, foto].
 *
 * @package amuma
 */

list( $amuma_name, $amuma_role, $amuma_photo ) = $args['p'];
$amuma_src = amuma_upload_url( $amuma_photo );
?>
<figure class="person" data-reveal>
	<div class="person__photo">
		<?php if ( $amuma_src ) : ?>
			<img src="<?php echo esc_url( $amuma_src ); ?>" alt="<?php echo esc_attr( $amuma_name ); ?>" loading="lazy">
		<?php else : ?>
			<span class="media-fallback"><?php echo amuma_ribbon_mini(); // phpcs:ignore ?></span>
		<?php endif; ?>
	</div>
	<figcaption>
		<span class="person__name"><?php echo esc_html( $amuma_name ); ?></span>
		<span class="person__role"><?php echo esc_html( $amuma_role ); ?></span>
	</figcaption>
</figure>
