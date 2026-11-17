<?php
/**
 * Noticia / actividad.
 *
 * @package amuma
 */

get_header();

while ( have_posts() ) :
	the_post();
	$amuma_cat   = amuma_primary_category();
	$amuma_url   = rawurlencode( get_permalink() );
	$amuma_title = rawurlencode( get_the_title() );
	?>
	<article <?php post_class( 'single' ); ?>>
		<header class="single__head">
			<div class="single__ribbon" aria-hidden="true"><?php get_template_part( 'template-parts/ribbon', null, array( 'id' => 'single-ribbon', 'class' => 'ribbon--page' ) ); ?></div>
			<div class="container container--narrow">
				<a class="back" href="<?php echo esc_url( amuma_news_url() ); ?>" data-fade><?php echo amuma_icon( 'chevron-l' ); // phpcs:ignore ?> <?php esc_html_e( 'Noticias', 'amuma' ); ?></a>
				<div class="single__meta" data-fade>
					<?php if ( $amuma_cat ) : ?>
						<a class="pill pill--<?php echo esc_attr( $amuma_cat->slug ); ?>" href="<?php echo esc_url( get_category_link( $amuma_cat ) ); ?>"><?php echo esc_html( $amuma_cat->name ); ?></a>
					<?php endif; ?>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<?php $amuma_photos = amuma_photo_count(); ?>
					<?php if ( $amuma_photos > 1 ) : ?>
						<span class="single__photos"><?php echo amuma_icon( 'camera' ); // phpcs:ignore ?> <?php /* translators: %d: número de fotos */ printf( esc_html( _n( '%d foto', '%d fotos', $amuma_photos, 'amuma' ) ), (int) $amuma_photos ); ?></span>
					<?php endif; ?>
				</div>
				<h1 class="single__title" data-hero-title><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="single__lead" data-fade><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="single__cover container" data-cover>
				<div class="single__cover-inner">
					<?php the_post_thumbnail( 'amuma-hero', array( 'data-lightbox-src' => get_the_post_thumbnail_url( null, 'full' ) ) ); ?>
				</div>
			</figure>
		<?php endif; ?>

		<div class="container container--narrow">
			<div class="entry-content" data-lightbox-scope>
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>

			<div class="share" aria-label="<?php esc_attr_e( 'Compartir', 'amuma' ); ?>">
				<span class="share__label"><?php esc_html_e( 'Compartir', 'amuma' ); ?></span>
				<a class="share__btn" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo $amuma_title . '%20' . $amuma_url; // phpcs:ignore ?>" aria-label="WhatsApp"><?php echo amuma_icon( 'whatsapp' ); // phpcs:ignore ?></a>
				<a class="share__btn" target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $amuma_url; // phpcs:ignore ?>" aria-label="Facebook"><?php echo amuma_icon( 'facebook' ); // phpcs:ignore ?></a>
				<a class="share__btn" target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?text=<?php echo $amuma_title; // phpcs:ignore ?>&url=<?php echo $amuma_url; // phpcs:ignore ?>" aria-label="X"><?php echo amuma_icon( 'x' ); // phpcs:ignore ?></a>
				<button class="share__btn" type="button" data-copy="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Copiar enlace', 'amuma' ); ?>"><?php echo amuma_icon( 'link' ); // phpcs:ignore ?></button>
			</div>
		</div>

		<?php
		$amuma_prev = get_previous_post();
		$amuma_next = get_next_post();
		if ( $amuma_prev || $amuma_next ) :
			?>
			<nav class="post-nav container" aria-label="<?php esc_attr_e( 'Más entradas', 'amuma' ); ?>">
				<?php if ( $amuma_prev ) : ?>
					<a class="post-nav__link post-nav__link--prev" href="<?php echo esc_url( get_permalink( $amuma_prev ) ); ?>">
						<span class="post-nav__dir"><?php echo amuma_icon( 'chevron-l' ); // phpcs:ignore ?> <?php esc_html_e( 'Anterior', 'amuma' ); ?></span>
						<span class="post-nav__title"><?php echo esc_html( get_the_title( $amuma_prev ) ); ?></span>
					</a>
				<?php else : ?>
					<span></span>
				<?php endif; ?>
				<?php if ( $amuma_next ) : ?>
					<a class="post-nav__link post-nav__link--next" href="<?php echo esc_url( get_permalink( $amuma_next ) ); ?>">
						<span class="post-nav__dir"><?php esc_html_e( 'Siguiente', 'amuma' ); ?> <?php echo amuma_icon( 'chevron-r' ); // phpcs:ignore ?></span>
						<span class="post-nav__title"><?php echo esc_html( get_the_title( $amuma_next ) ); ?></span>
					</a>
				<?php endif; ?>
			</nav>
		<?php endif; ?>
	</article>

	<?php
	$amuma_related = new WP_Query(
		array(
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'cat'                 => $amuma_cat ? $amuma_cat->term_id : 0,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( $amuma_related->have_posts() ) :
		?>
		<section class="section related">
			<div class="container">
				<div class="section__head section__head--row">
					<h2 class="section__title section__title--sm" data-split><?php esc_html_e( 'También te puede interesar', 'amuma' ); ?></h2>
					<a class="btn btn--ghost" data-magnetic href="<?php echo esc_url( amuma_news_url() ); ?>"><span><?php esc_html_e( 'Ver todas', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></a>
				</div>
				<div class="news-grid news-grid--3">
					<?php
					while ( $amuma_related->have_posts() ) :
						$amuma_related->the_post();
						get_template_part( 'template-parts/card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;

get_footer();
