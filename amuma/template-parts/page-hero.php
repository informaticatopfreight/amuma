<?php
/**
 * Cabecera de página interior.
 * $args: title, intro, kicker, image (ruta en uploads), id.
 *
 * @package amuma
 */

$amuma_title  = isset( $args['title'] ) ? $args['title'] : get_the_title();
$amuma_intro  = isset( $args['intro'] ) ? $args['intro'] : '';
$amuma_kicker = isset( $args['kicker'] ) ? $args['kicker'] : 'AMUMA';
$amuma_img    = isset( $args['image'] ) ? amuma_upload_url( $args['image'] ) : '';
?>
<section class="page-hero<?php echo $amuma_img ? ' page-hero--image' : ''; ?>">
	<div class="page-hero__ribbon" aria-hidden="true"><?php get_template_part( 'template-parts/ribbon', null, array( 'id' => 'ph-ribbon', 'class' => 'ribbon--page' ) ); ?></div>
	<div class="container page-hero__grid">
		<div class="page-hero__copy">
			<p class="kicker" data-fade><?php echo esc_html( $amuma_kicker ); ?></p>
			<h1 class="page-hero__title" data-hero-title><?php echo amuma_em_last_word( $amuma_title ); // phpcs:ignore ?></h1>
			<?php if ( $amuma_intro ) : ?>
				<p class="page-hero__intro" data-fade><?php echo esc_html( $amuma_intro ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $amuma_img ) : ?>
			<figure class="page-hero__media" data-cover>
				<div class="single__cover-inner"><img src="<?php echo esc_url( $amuma_img ); ?>" alt="" loading="eager"></div>
			</figure>
		<?php endif; ?>
	</div>
</section>
