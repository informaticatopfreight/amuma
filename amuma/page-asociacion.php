<?php
/**
 * Página "Asociación" (slug: asociacion).
 * El contenido está en inc/content.php.
 *
 * @package amuma
 */

get_header();
$c = amuma_content( 'asociacion' );

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'kicker' => __( 'La asociación', 'amuma' ),
		'title'  => __( 'Desde 1997, a tu lado', 'amuma' ),
		'intro'  => $c['intro'],
		'image'  => '2021/03/women-fighting-breast-cancer.jpg',
	)
);
?>

<section class="section section--tight">
	<div class="container">
		<p class="kicker" data-fade><?php esc_html_e( 'Quiénes somos', 'amuma' ); ?></p>
		<p class="about__statement" data-scrub-text><?php echo esc_html( $c['quienes'] ); ?></p>
		<div class="facts">
			<div class="fact" data-reveal>
				<span class="fact__big">19<small> oct</small></span>
				<p><?php echo esc_html( $c['hito'] ); ?></p>
			</div>
			<div class="fact" data-reveal>
				<span class="fact__big">CLM</span>
				<p><?php echo esc_html( $c['ambito'] ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="section mission">
	<div class="container mission__grid">
		<div class="mission__head">
			<p class="kicker" data-fade><?php esc_html_e( 'Misión', 'amuma' ); ?></p>
			<h2 class="section__title" data-split><?php esc_html_e( 'Lo que nos mueve', 'amuma' ); ?></h2>
		</div>
		<ol class="mission__list">
			<?php foreach ( $c['mision'] as $amuma_item ) : ?>
				<li data-reveal><?php echo esc_html( $amuma_item ); ?></li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="section team">
	<div class="container">
		<div class="section__head">
			<p class="kicker" data-fade><?php esc_html_e( 'El equipo', 'amuma' ); ?></p>
			<h2 class="section__title" data-split><?php esc_html_e( 'Las personas de AMUMA', 'amuma' ); ?></h2>
			<p class="section__lead" data-fade><?php echo esc_html( $c['equipo'] ); ?></p>
		</div>

		<h3 class="team__group" data-fade><?php esc_html_e( 'Junta Directiva', 'amuma' ); ?></h3>
		<div class="team__grid">
			<?php foreach ( $c['junta'] as $amuma_p ) : ?>
				<?php get_template_part( 'template-parts/person', null, array( 'p' => $amuma_p ) ); ?>
			<?php endforeach; ?>
		</div>
		<p class="team__note" data-fade><?php echo esc_html( $c['junta_nota'] ); ?></p>

		<h3 class="team__group" data-fade><?php esc_html_e( 'Profesionales', 'amuma' ); ?></h3>
		<div class="team__grid team__grid--small">
			<?php foreach ( $c['profesionales'] as $amuma_p ) : ?>
				<?php get_template_part( 'template-parts/person', null, array( 'p' => $amuma_p ) ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--tight">
	<div class="container duo">
		<article class="duo__card duo__card--photo" data-reveal>
			<?php $amuma_vol = amuma_upload_url( $c['voluntariado_img'] ); ?>
			<?php if ( $amuma_vol ) : ?>
				<div class="duo__img"><img src="<?php echo esc_url( $amuma_vol ); ?>" alt="<?php esc_attr_e( 'Voluntariado de AMUMA', 'amuma' ); ?>" loading="lazy"></div>
			<?php endif; ?>
			<div class="duo__body">
				<h3><?php esc_html_e( 'Voluntariado', 'amuma' ); ?></h3>
				<p><?php echo esc_html( $c['voluntariado'] ); ?></p>
				<a class="link-arrow" href="<?php echo esc_url( amuma_page_url( 'contacto', '/#contacto' ) ); ?>"><?php esc_html_e( 'Quiero ser voluntaria', 'amuma' ); ?> <?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></a>
			</div>
		</article>
		<article class="duo__card duo__card--pink" data-reveal>
			<div class="duo__body">
				<span class="duo__icon"><?php echo amuma_icon( 'people' ); // phpcs:ignore ?></span>
				<h3><?php esc_html_e( 'Colaboradores', 'amuma' ); ?></h3>
				<p><?php echo esc_html( $c['colaboradores'] ); ?></p>
			</div>
		</article>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
