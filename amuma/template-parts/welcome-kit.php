<?php
/**
 * "Si acabas de recibir el diagnóstico": acogida y kit de bienvenida.
 *
 * @package amuma
 */

$amuma_n = amuma_content( 'servicios' )['nuevas'];
?>
<section class="section welcome" id="recien-diagnosticada">
	<div class="welcome__ribbon" aria-hidden="true"><?php get_template_part( 'template-parts/ribbon', null, array( 'id' => 'welcome-ribbon', 'class' => 'ribbon--welcome' ) ); ?></div>
	<div class="container welcome__grid">
		<div class="welcome__head">
			<p class="kicker" data-fade><?php esc_html_e( 'Nuevas socias', 'amuma' ); ?></p>
			<h2 class="section__title" data-split><?php echo esc_html( $amuma_n['title'] ); ?></h2>
			<?php foreach ( $amuma_n['text'] as $amuma_p ) : ?>
				<p class="welcome__text" data-fade><?php echo esc_html( $amuma_p ); ?></p>
			<?php endforeach; ?>
			<p class="welcome__text" data-fade><strong><?php esc_html_e( 'Y te damos un pequeño kit de bienvenida:', 'amuma' ); ?></strong></p>
		</div>
		<ul class="kit">
			<?php foreach ( $amuma_n['kit'] as $amuma_k ) : ?>
				<?php list( $amuma_t, $amuma_d, $amuma_im ) = $amuma_k; ?>
				<?php $amuma_src = amuma_upload_url( $amuma_im ); ?>
				<li class="kit__item<?php echo $amuma_src ? ' kit__item--photo' : ''; ?>" data-reveal data-tilt>
					<?php if ( $amuma_src ) : ?>
						<span class="kit__img"><img src="<?php echo esc_url( $amuma_src ); ?>" alt="" loading="lazy"></span>
					<?php else : ?>
						<span class="kit__icon"><?php echo amuma_icon( 'heart' ); // phpcs:ignore ?></span>
					<?php endif; ?>
					<span class="kit__title"><?php echo esc_html( $amuma_t ); ?></span>
					<span class="kit__text"><?php echo esc_html( $amuma_d ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
