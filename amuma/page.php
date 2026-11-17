<?php
/**
 * Páginas (Quiénes somos, Servicios, Contacto, Aviso legal…).
 *
 * @package amuma
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="page-hero">
		<div class="page-hero__ribbon" aria-hidden="true"><?php get_template_part( 'template-parts/ribbon', null, array( 'id' => 'page-ribbon', 'class' => 'ribbon--page' ) ); ?></div>
		<div class="container">
			<p class="kicker" data-fade>AMUMA</p>
			<h1 class="page-hero__title" data-hero-title><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="page-hero__intro" data-fade><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="single__cover container" data-cover>
			<div class="single__cover-inner"><?php the_post_thumbnail( 'amuma-hero' ); ?></div>
		</figure>
	<?php endif; ?>

	<section class="section section--tight">
		<div class="container container--narrow">
			<div class="entry-content" data-lightbox-scope>
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
