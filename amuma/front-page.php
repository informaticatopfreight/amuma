<?php
/**
 * Portada.
 *
 * @package amuma
 */

get_header();

$amuma_news = new WP_Query(
	array(
		'posts_per_page'      => 8,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>

<!-- ============ HERO ============ -->
<section class="hero" data-hero>
	<div class="hero__blobs" aria-hidden="true"><i></i><i></i><i></i></div>
	<div class="container hero__grid">
		<div class="hero__copy">
			<p class="eyebrow hero__eyebrow" data-fade><?php echo esc_html( amuma_opt( 'hero_eyebrow' ) ); ?></p>
			<h1 class="hero__title" data-hero-title><?php echo amuma_em_last_word( amuma_opt( 'hero_title' ) ); // phpcs:ignore ?></h1>
			<p class="hero__text" data-fade><?php echo esc_html( amuma_opt( 'hero_text' ) ); ?></p>
			<div class="hero__actions" data-fade>
				<a class="btn btn--pink" data-magnetic href="<?php echo esc_url( amuma_opt( 'hero_btn1_url' ) ); ?>"><span><?php echo esc_html( amuma_opt( 'hero_btn1' ) ); ?></span><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></a>
				<a class="btn btn--ghost" data-magnetic href="<?php echo esc_url( amuma_opt( 'hero_btn2_url' ) ); ?>"><span><?php echo esc_html( amuma_opt( 'hero_btn2' ) ); ?></span></a>
			</div>
		</div>
		<div class="hero__visual" data-hero-visual>
			<div class="hero__halo" aria-hidden="true"></div>
			<?php get_template_part( 'template-parts/ribbon', null, array( 'id' => 'hero-ribbon', 'class' => 'ribbon--hero' ) ); ?>
			<svg class="hero__orbit" viewBox="0 0 400 400" aria-hidden="true">
				<defs><path id="orbit-path" d="M200,200 m-170,0 a170,170 0 1,1 340,0 a170,170 0 1,1 -340,0"/></defs>
				<text><textPath href="#orbit-path"><?php echo esc_html( mb_strtoupper( implode( ' · ', amuma_list( amuma_opt( 'marquee' ) ) ) . ' · ' ) ); ?></textPath></text>
			</svg>
		</div>
	</div>
	<a class="hero__scroll" href="#quienes-somos" aria-label="<?php esc_attr_e( 'Bajar', 'amuma' ); ?>"><span></span></a>
</section>

<!-- ============ CINTA ============ -->
<div class="marquee" aria-hidden="true" data-marquee>
	<div class="marquee__track">
		<?php for ( $amuma_i = 0; $amuma_i < 2; $amuma_i++ ) : ?>
			<div class="marquee__group">
				<?php foreach ( amuma_list( amuma_opt( 'marquee' ) ) as $amuma_word ) : ?>
					<span class="marquee__word"><?php echo esc_html( $amuma_word ); ?></span>
					<?php echo amuma_ribbon_mini( 'marquee__ribbon' ); // phpcs:ignore ?>
				<?php endforeach; ?>
			</div>
		<?php endfor; ?>
	</div>
</div>

<!-- ============ QUIÉNES SOMOS ============ -->
<section class="section about" id="quienes-somos">
	<div class="container">
		<p class="kicker" data-fade><?php echo esc_html( amuma_opt( 'about_kicker' ) ); ?></p>
		<p class="about__statement" data-scrub-text><?php echo esc_html( amuma_opt( 'about_text' ) ); ?></p>

		<div class="stats">
			<?php for ( $amuma_i = 1; $amuma_i <= 3; $amuma_i++ ) : ?>
				<?php if ( amuma_opt( "stat{$amuma_i}_label" ) ) : ?>
					<div class="stat" data-reveal>
						<span class="stat__num"><span data-count="<?php echo esc_attr( amuma_opt( "stat{$amuma_i}_num" ) ); ?>"><?php echo esc_html( amuma_opt( "stat{$amuma_i}_num" ) ); ?></span><?php echo esc_html( amuma_opt( "stat{$amuma_i}_suffix" ) ); ?></span>
						<span class="stat__label"><?php echo esc_html( amuma_opt( "stat{$amuma_i}_label" ) ); ?></span>
					</div>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	</div>
</section>

<!-- ============ QUÉ HACEMOS ============ -->
<section class="section services" id="que-hacemos">
	<div class="container">
		<div class="section__head">
			<p class="kicker" data-fade>02</p>
			<h2 class="section__title" data-split><?php echo esc_html( amuma_opt( 'services_title' ) ); ?></h2>
		</div>
		<div class="services__grid">
			<?php
			$amuma_icons = array( 1 => 'heart', 2 => 'body', 3 => 'info', 4 => 'people' );
			for ( $amuma_i = 1; $amuma_i <= 4; $amuma_i++ ) :
				if ( ! amuma_opt( "service{$amuma_i}_title" ) ) {
					continue;
				}
				?>
				<article class="service" data-reveal data-tilt>
					<span class="service__num">0<?php echo esc_html( $amuma_i ); ?></span>
					<span class="service__icon"><?php echo amuma_icon( $amuma_icons[ $amuma_i ] ); // phpcs:ignore ?></span>
					<h3 class="service__title"><?php echo esc_html( amuma_opt( "service{$amuma_i}_title" ) ); ?></h3>
					<p class="service__text"><?php echo esc_html( amuma_opt( "service{$amuma_i}_text" ) ); ?></p>
				</article>
			<?php endfor; ?>
		</div>
	</div>
</section>

<!-- ============ ACTUALIDAD ============ -->
<?php if ( $amuma_news->have_posts() ) : ?>
<section class="section news-reel" id="actualidad" data-reel>
	<div class="news-reel__pin">
		<div class="container news-reel__head">
			<div>
				<p class="kicker" data-fade>03</p>
				<h2 class="section__title" data-split><?php esc_html_e( 'Actualidad', 'amuma' ); ?></h2>
			</div>
			<a class="btn btn--ghost" data-magnetic href="<?php echo esc_url( amuma_news_url() ); ?>"><span><?php esc_html_e( 'Ver todas', 'amuma' ); ?></span><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></a>
		</div>
		<div class="news-reel__track" data-reel-track>
			<?php
			while ( $amuma_news->have_posts() ) :
				$amuma_news->the_post();
				get_template_part( 'template-parts/card', null, array( 'variant' => 'slide' ) );
			endwhile;
			wp_reset_postdata();
			?>
			<a class="news-reel__all" href="<?php echo esc_url( amuma_news_url() ); ?>">
				<?php echo amuma_ribbon_mini(); // phpcs:ignore ?>
				<span><?php esc_html_e( 'Todas las noticias y actividades', 'amuma' ); ?></span>
				<?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?>
			</a>
		</div>
		<div class="news-reel__progress" aria-hidden="true"><span data-reel-progress></span></div>
	</div>
</section>
<?php endif; ?>

<!-- ============ COLABORA ============ -->
<section class="section cta" id="colabora">
	<div class="container">
		<div class="section__head">
			<p class="kicker" data-fade>04</p>
			<h2 class="section__title" data-split><?php echo esc_html( amuma_opt( 'cta_title' ) ); ?></h2>
		</div>
		<ul class="cta__list">
			<?php for ( $amuma_i = 1; $amuma_i <= 3; $amuma_i++ ) : ?>
				<?php if ( amuma_opt( "cta{$amuma_i}_title" ) ) : ?>
					<li class="cta__item" data-reveal>
						<a href="<?php echo esc_url( amuma_opt( "cta{$amuma_i}_url" ) ); ?>">
							<span class="cta__num">0<?php echo esc_html( $amuma_i ); ?></span>
							<span class="cta__title"><?php echo esc_html( amuma_opt( "cta{$amuma_i}_title" ) ); ?></span>
							<span class="cta__text"><?php echo esc_html( amuma_opt( "cta{$amuma_i}_text" ) ); ?></span>
							<span class="cta__arrow"><?php echo amuma_icon( 'arrow' ); // phpcs:ignore ?></span>
						</a>
					</li>
				<?php endif; ?>
			<?php endfor; ?>
		</ul>
	</div>
</section>

<!-- ============ CONTACTO ============ -->
<section class="section contact" id="contacto">
	<div class="container contact__grid">
		<div>
			<p class="kicker" data-fade>05</p>
			<h2 class="section__title" data-split><?php esc_html_e( 'Estamos aquí', 'amuma' ); ?></h2>
			<p class="contact__lead" data-fade><?php esc_html_e( 'Llámanos, escríbenos o ven a vernos. Te escuchamos sin prisas.', 'amuma' ); ?></p>
		</div>
		<div class="contact__cards">
			<a class="contact__card" data-reveal href="<?php echo esc_url( amuma_opt( 'maps_url' ) ); ?>" target="_blank" rel="noopener">
				<?php echo amuma_icon( 'pin' ); // phpcs:ignore ?>
				<span class="contact__label"><?php esc_html_e( 'Sede · Ciudad Real', 'amuma' ); ?></span>
				<span class="contact__value"><?php echo nl2br( esc_html( amuma_opt( 'address' ) ) ); ?></span>
			</a>
			<div class="contact__card" data-reveal>
				<?php echo amuma_icon( 'phone' ); // phpcs:ignore ?>
				<span class="contact__label"><?php esc_html_e( 'Teléfonos', 'amuma' ); ?></span>
				<span class="contact__value">
					<?php foreach ( array( 'phone1', 'phone2' ) as $amuma_ph ) : ?>
						<?php if ( amuma_opt( $amuma_ph ) ) : ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', amuma_opt( $amuma_ph ) ) ); ?>"><?php echo esc_html( amuma_opt( $amuma_ph ) ); ?></a><br>
						<?php endif; ?>
					<?php endforeach; ?>
				</span>
			</div>
			<a class="contact__card" data-reveal href="mailto:<?php echo esc_attr( antispambot( amuma_opt( 'email' ) ) ); ?>">
				<?php echo amuma_icon( 'mail' ); // phpcs:ignore ?>
				<span class="contact__label"><?php esc_html_e( 'Email', 'amuma' ); ?></span>
				<span class="contact__value"><?php echo esc_html( antispambot( amuma_opt( 'email' ) ) ); ?></span>
			</a>
			<div class="contact__card" data-reveal>
				<?php echo amuma_icon( 'clock' ); // phpcs:ignore ?>
				<span class="contact__label"><?php esc_html_e( 'Horario', 'amuma' ); ?></span>
				<span class="contact__value"><?php echo esc_html( amuma_opt( 'hours' ) ); ?></span>
			</div>
		</div>
	</div>
</section>

<?php
// Si la página de portada tiene contenido escrito en el editor, se muestra aquí debajo.
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		if ( trim( get_the_content() ) ) :
			?>
			<section class="section">
				<div class="container entry-content"><?php the_content(); ?></div>
			</section>
			<?php
		endif;
	endwhile;
endif;

get_footer();
