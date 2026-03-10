<?php
get_header();

$h1             = get_field( 'front_h1' ) ?: 'Наши услуги:';
$services       = get_field( 'front_services' );
$btn_dev_text   = get_field( 'front_btn_dev_text' ) ?: 'Разработка электроники';
$btn_dev_url    = get_field( 'front_btn_dev_url' ) ?: '#';
$btn_prod_text  = get_field( 'front_btn_prod_text' ) ?: 'Производство электроники';
$btn_prod_url   = get_field( 'front_btn_prod_url' ) ?: '#';
$top_image      = get_field( 'front_top_image' ) ?: get_template_directory_uri() . '/assets/img/top-img.png';
$slogan_title   = get_field( 'front_slogan_title' ) ?: 'Наш принцип:';
$slogan_text    = get_field( 'front_slogan_text' ) ?: 'Ваша идея - наша реализация';
$form_title     = get_field( 'front_form_title' ) ?: 'Свяжитесь с нами';
?>

<main class="main-page">
	<section class="top-block">
		<div class="container">
			<div class="top-block-inner grid">
				<div class="text-block">
					<div class="title"><h1><?php echo esc_html( $h1 ); ?></h1></div>
					<div class="list">
						<ul>
							<?php if ( $services ) : ?>
								<?php foreach ( $services as $service ) : ?>
									<li><?php echo esc_html( $service['service_text'] ); ?></li>
								<?php endforeach; ?>
							<?php else : ?>
								<li>разработка электронных устройств и встраиваемых систем;</li>
								<li>контрактное производство электроники.</li>
							<?php endif; ?>
						</ul>
					</div>
					<div class="btn-block">
						<a href="<?php echo esc_url( $btn_dev_url ); ?>" class="btn"><?php echo esc_html( $btn_dev_text ); ?></a>
						<a href="<?php echo esc_url( $btn_prod_url ); ?>" class="btn"><?php echo esc_html( $btn_prod_text ); ?></a>
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
					<h3><?php echo esc_html( $form_title ); ?></h3>
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
