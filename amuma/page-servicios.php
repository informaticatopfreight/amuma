<?php
/**
 * Página "Servicios" (slug: servicios).
 * El contenido está en inc/content.php.
 *
 * @package amuma
 */

get_header();
$c = amuma_content( 'servicios' );

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'kicker' => __( 'Servicios', 'amuma' ),
		'title'  => __( 'Cómo te ayudamos', 'amuma' ),
		'intro'  => $c['intro'],
		'image'  => '2021/02/this-women-support-the-fight-against-breast-cancer.jpg',
	)
);
?>

<nav class="service-nav container" aria-label="<?php esc_attr_e( 'Servicios', 'amuma' ); ?>" data-fade>
	<?php foreach ( $c['lista'] as $amuma_s ) : ?>
		<a href="#<?php echo esc_attr( $amuma_s['slug'] ); ?>"><?php echo amuma_icon( $amuma_s['icon'] ); // phpcs:ignore ?> <?php echo esc_html( $amuma_s['title'] ); ?></a>
	<?php endforeach; ?>
</nav>

<section class="section section--tight">
	<div class="container service-rows">
		<?php foreach ( $c['lista'] as $amuma_i => $amuma_s ) : ?>
			<?php $amuma_img = amuma_upload_url( $amuma_s['img'] ); ?>
			<article class="service-row<?php echo $amuma_i % 2 ? ' service-row--flip' : ''; ?>" id="<?php echo esc_attr( $amuma_s['slug'] ); ?>">
				<div class="service-row__media" data-reveal>
					<?php if ( $amuma_img ) : ?>
						<div class="service-row__img" data-parallax-img><img src="<?php echo esc_url( $amuma_img ); ?>" alt="" loading="lazy"></div>
					<?php else : ?>
						<span class="media-fallback"><?php echo amuma_ribbon_mini(); // phpcs:ignore ?></span>
					<?php endif; ?>
					<span class="service-row__icon"><?php echo amuma_icon( $amuma_s['icon'] ); // phpcs:ignore ?></span>
				</div>
				<div class="service-row__body" data-reveal>
					<span class="service-row__num"><?php echo esc_html( sprintf( '%02d', $amuma_i + 1 ) ); ?></span>
					<h2 class="service-row__title"><?php echo esc_html( $amuma_s['title'] ); ?></h2>
					<?php foreach ( $amuma_s['text'] as $amuma_p ) : ?>
						<p><?php echo esc_html( $amuma_p ); ?></p>
					<?php endforeach; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/welcome-kit' ); ?>

<section class="section wellbeing">
	<div class="container">
		<div class="section__head">
			<p class="kicker" data-fade><?php esc_html_e( 'Otros servicios', 'amuma' ); ?></p>
			<h2 class="section__title" data-split><?php esc_html_e( 'Para sentirte mejor', 'amuma' ); ?></h2>
			<p class="section__lead" data-fade><?php esc_html_e( 'Actividades que mejoran el estado de ánimo, el bienestar y la calidad de vida de las personas afectadas.', 'amuma' ); ?></p>
		</div>
		<div class="wellbeing__grid">
			<?php foreach ( $c['otros'] as $amuma_o ) : ?>
				<?php list( $amuma_t, $amuma_d, $amuma_im ) = $amuma_o; ?>
				<?php $amuma_src = amuma_upload_url( $amuma_im ); ?>
				<article class="well" data-reveal>
					<div class="well__img<?php echo false !== strpos( $amuma_im, 'Arqueros' ) ? ' well__img--logo' : ''; ?>">
						<?php if ( $amuma_src ) : ?>
							<img src="<?php echo esc_url( $amuma_src ); ?>" alt="" loading="lazy">
						<?php else : ?>
							<span class="media-fallback"><?php echo amuma_ribbon_mini(); // phpcs:ignore ?></span>
						<?php endif; ?>
					</div>
					<h3 class="well__title"><?php echo esc_html( $amuma_t ); ?></h3>
					<p class="well__text"><?php echo esc_html( $amuma_d ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
