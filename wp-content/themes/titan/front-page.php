<?php
get_header();

// Поля из существующей ACF-группы "Главная страница" (group_698079284b737)
$h1           = get_field( 'top_title' ) ?: 'Наши услуги:';
$top_image    = get_field( 'top_bg_img' ) ?: get_template_directory_uri() . '/assets/img/top-img.png';
$description  = get_field( 'top_description' );
$slogan_title = get_field( 'medium_title' ) ?: 'Наш принцип:';
$slogan_text  = get_field( 'medium_description' ) ?: 'Ваша идея - наша реализация';
?>

<main class="main-page">
	<section class="top-block">
		<div class="container">
			<div class="top-block-inner grid">
				<div class="text-block">
					<div class="title"><h1><?php echo esc_html( $h1 ); ?></h1></div>
					<?php if ( $description ) : ?>
						<div class="list">
							<?php echo wp_kses_post( $description ); ?>
						</div>
					<?php else : ?>
						<div class="list">
							<ul>
								<li>разработка электронных устройств и встраиваемых систем;</li>
								<li>контрактное производство электроники.</li>
							</ul>
						</div>
					<?php endif; ?>
					<div class="btn-block">
						<a href="#" class="btn">Разработка электроники</a>
						<a href="#" class="btn">Производство электроники</a>
					</div>
				</div>
				<div class="img">
					<img src="<?php echo esc_url( $top_image ); ?>" alt="<?php echo esc_attr( $h1 ); ?>">
				</div>
			</div>
		</div>
	</section>

	<section class="slogan-block">
		<div class="container">
			<div class="text-block">
				<div class="title">
					<h3><?php echo esc_html( $slogan_title ); ?></h3>
				</div>
				<div class="text"><?php echo esc_html( $slogan_text ); ?></div>
			</div>
		</div>
	</section>

	<section class="contacts-form">
		<div class="container">
			<div class="grid">
				<div class="title">
					<h3>Свяжитесь с нами</h3>
				</div>
				<div class="form-block">
					<?php echo do_shortcode( '[contact-form-7 title="Свяжитесь с нами"]' ); ?>
				</div>
			</div>
		</div>
		<div class="file-error">
			<div class="name"></div>
			<div class="error-text">Файл слишком большой</div>
		</div>
	</section>
</main>

<?php get_template_part( 'template-parts/cf7-scripts' ); ?>

<?php get_footer(); ?>
