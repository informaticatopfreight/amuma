<?php
/**
 * Tarjeta de noticia / actividad.
 * $args['variant']: 'grid' (por defecto), 'featured' o 'slide'.
 *
 * @package amuma
 */

$amuma_variant = isset( $args['variant'] ) ? $args['variant'] : 'grid';
$amuma_photos  = amuma_photo_count();
$amuma_size    = 'featured' === $amuma_variant ? 'amuma-wide' : 'amuma-card';
?>
<article <?php post_class( 'card card--' . $amuma_variant ); ?> data-reveal>
	<a class="card__link" href="<?php the_permalink(); ?>">
		<div class="card__media" data-cursor="<?php esc_attr_e( 'Leer', 'amuma' ); ?>">
			<div class="card__img" data-parallax-img>
				<?php echo amuma_post_image_html( null, $amuma_size ); // phpcs:ignore ?>
			</div>
			<?php if ( $amuma_photos > 1 ) : ?>
				<span class="card__photos"><?php echo amuma_icon( 'camera' ); // phpcs:ignore ?> <?php echo esc_html( $amuma_photos ); ?></span>
			<?php endif; ?>
		</div>
		<div class="card__body">
			<div class="card__meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></time>
			</div>
			<h3 class="card__title"><span><?php the_title(); ?></span></h3>
			<?php if ( 'featured' === $amuma_variant ) : ?>
				<p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34 ) ); ?></p>
			<?php endif; ?>
			<span class="card__more"><?php esc_html_e( 'Leer más', 'amuma' ); ?> <?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></span>
		</div>
	</a>
</article>
