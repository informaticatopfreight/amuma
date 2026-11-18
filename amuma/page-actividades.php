<?php
/**
 * Página "Actividades" (slug: actividades).
 * Actividades fijas (inc/content.php), proyecto "Tejiendo por la vida" y las últimas noticias.
 *
 * @package amuma
 */

get_header();
$c      = amuma_content( 'actividades' );
$latest = new WP_Query(
	array(
		'posts_per_page'      => 6,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'kicker' => __( 'Actualidad · Actividades', 'amuma' ),
		'title'  => __( 'Nos movemos juntas', 'amuma' ),
		'intro'  => $c['intro'],
	)
);
?>

<section class="section section--tight">
	<div class="container">
		<div class="section__head">
			<p class="kicker" data-fade><?php esc_html_e( 'Cada año', 'amuma' ); ?></p>
			<h2 class="section__title section__title--sm" data-split><?php esc_html_e( 'Nuestras citas fijas', 'amuma' ); ?></h2>
		</div>
		<div class="fixed-acts">
			<?php foreach ( $c['fijas'] as $amuma_i => $amuma_a ) : ?>
				<?php list( $amuma_t, $amuma_d, $amuma_im ) = $amuma_a; ?>
				<?php $amuma_src = amuma_upload_url( $amuma_im ); ?>
				<article class="fixed-act<?php echo $amuma_src ? '' : ' fixed-act--plain'; ?>" data-reveal>
					<?php if ( $amuma_src ) : ?>
						<div class="fixed-act__img" data-parallax-img><img src="<?php echo esc_url( $amuma_src ); ?>" alt="" loading="lazy"></div>
					<?php else : ?>
						<div class="fixed-act__ribbon" aria-hidden="true"><?php echo amuma_ribbon_mini(); // phpcs:ignore ?></div>
					<?php endif; ?>
					<div class="fixed-act__body">
						<h3><?php echo esc_html( $amuma_t ); ?></h3>
						<p><?php echo esc_html( $amuma_d ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<div class="marquee marquee--soft" aria-hidden="true" data-marquee>
	<div class="marquee__track">
		<?php for ( $amuma_k = 0; $amuma_k < 2; $amuma_k++ ) : ?>
			<div class="marquee__group">
				<?php foreach ( $c['variables'] as $amuma_word ) : ?>
					<span class="marquee__word"><?php echo esc_html( $amuma_word ); ?></span>
					<?php echo amuma_ribbon_mini( 'marquee__ribbon' ); // phpcs:ignore ?>
				<?php endforeach; ?>
			</div>
		<?php endfor; ?>
	</div>
</div>
<p class="screen-reader-text"><?php echo esc_html( implode( ', ', $c['variables'] ) ); ?></p>

<?php
$amuma_tj     = $c['tejiendo'];
$amuma_video  = amuma_upload_url( $amuma_tj['video'] );
$amuma_poster = amuma_upload_url( $amuma_tj['poster'] );
if ( $amuma_video || $amuma_poster ) :
	?>
	<section class="section knit">
		<div class="container knit__grid">
			<div class="knit__copy">
				<p class="kicker" data-fade><?php esc_html_e( 'Proyecto', 'amuma' ); ?></p>
				<h2 class="section__title" data-split><?php echo esc_html( $amuma_tj['title'] ); ?></h2>
				<p class="section__lead" data-fade><?php echo esc_html( $amuma_tj['text'] ); ?></p>
			</div>
			<div class="knit__media" data-reveal>
				<?php if ( $amuma_video ) : ?>
					<video controls preload="none" playsinline <?php echo $amuma_poster ? 'poster="' . esc_url( $amuma_poster ) . '"' : ''; ?>>
						<source src="<?php echo esc_url( $amuma_video ); ?>" type="video/mp4">
					</video>
				<?php else : ?>
					<img src="<?php echo esc_url( $amuma_poster ); ?>" alt="<?php echo esc_attr( $amuma_tj['title'] ); ?>" loading="lazy">
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( $latest->have_posts() ) : ?>
	<section class="section">
		<div class="container">
			<div class="section__head section__head--row">
				<div>
					<p class="kicker" data-fade><?php esc_html_e( 'Lo último', 'amuma' ); ?></p>
					<h2 class="section__title section__title--sm" data-split><?php esc_html_e( 'Últimas noticias', 'amuma' ); ?></h2>
				</div>
				<a class="btn btn--ghost" data-magnetic href="<?php echo esc_url( amuma_news_url() ); ?>"><span><?php esc_html_e( 'Ver todas', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></a>
			</div>
			<div class="news-grid news-grid--3">
				<?php
				while ( $latest->have_posts() ) :
					$latest->the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
