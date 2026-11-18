<?php
/**
 * Listado de Actualidad (noticias y actividades). Lo usan home.php, archive.php y search.php.
 * $args['title'], $args['intro'].
 *
 * @package amuma
 */

$amuma_title   = isset( $args['title'] ) ? $args['title'] : __( 'Actualidad', 'amuma' );
$amuma_intro   = isset( $args['intro'] ) ? $args['intro'] : __( 'Noticias, actividades, talleres y encuentros de AMUMA.', 'amuma' );
$amuma_paged   = max( 1, get_query_var( 'paged' ) );
?>
<section class="page-hero page-hero--news">
	<div class="page-hero__ribbon" aria-hidden="true"><?php get_template_part( 'template-parts/ribbon', null, array( 'id' => 'list-ribbon', 'class' => 'ribbon--page' ) ); ?></div>
	<div class="container">
		<p class="kicker" data-fade><?php esc_html_e( 'AMUMA', 'amuma' ); ?></p>
		<h1 class="page-hero__title" data-hero-title><?php echo esc_html( $amuma_title ); ?></h1>
		<?php if ( $amuma_intro ) : ?>
			<p class="page-hero__intro" data-fade><?php echo wp_kses_post( $amuma_intro ); ?></p>
		<?php endif; ?>

		<?php $amuma_acts = get_page_by_path( 'actividades' ); ?>
		<?php if ( $amuma_acts && ! is_search() ) : ?>
			<a class="yearly" href="<?php echo esc_url( get_permalink( $amuma_acts ) ); ?>" data-fade>
				<?php echo amuma_ribbon_mini(); // phpcs:ignore ?>
				<span><strong><?php esc_html_e( 'Nuestras citas de cada año', 'amuma' ); ?></strong> <?php esc_html_e( 'Carrera Rosa, Cuestación, Mercadillo solidario y Tejiendo por la vida', 'amuma' ); ?></span>
				<?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?>
			</a>
		<?php endif; ?>
	</div>
</section>

<section class="section section--tight">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="news-grid" data-load-target>
				<?php
				$amuma_n = 0;
				while ( have_posts() ) :
					the_post();
					$amuma_variant = ( 0 === $amuma_n && 1 === $amuma_paged && ! is_search() ) ? 'featured' : 'grid';
					get_template_part( 'template-parts/card', null, array( 'variant' => $amuma_variant ) );
					$amuma_n++;
				endwhile;
				?>
			</div>

			<?php $amuma_next = get_next_posts_link( __( 'Ver más', 'amuma' ) ); ?>
			<?php if ( $amuma_next ) : ?>
				<div class="load-more">
					<a class="btn btn--pink" data-load-more href="<?php echo esc_url( next_posts( 0, false ) ); ?>"><span><?php esc_html_e( 'Ver más', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></a>
				</div>
			<?php endif; ?>

			<div class="pagination" data-pagination>
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => amuma_icon( 'chevron-l' ) . '<span class="screen-reader-text">' . __( 'Anterior', 'amuma' ) . '</span>',
						'next_text' => '<span class="screen-reader-text">' . __( 'Siguiente', 'amuma' ) . '</span>' . amuma_icon( 'chevron-r' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<div class="empty">
				<?php echo amuma_ribbon_mini(); // phpcs:ignore ?>
				<p><?php esc_html_e( 'Todavía no hay nada publicado aquí.', 'amuma' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
