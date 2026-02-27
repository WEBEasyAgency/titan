<?php get_header(); ?>

<main class="main-page">
	<section class="top-block">
		<div class="container">
			<div class="top-block-inner grid">
				<div class="text-block">
					<div class="title"><h1>Наши услуги:</h1></div>
					<div class="list">
						<ul>
							<li>разработка электронных устройств и встраиваемых систем;</li>
							<li>контрактное производство электроники.</li>
						</ul>
					</div>
					<div class="btn-block">
						<a href="#" class="btn">Разработка электроники</a>
						<a href="https://titan.realeasystudio.site/%d0%bf%d1%80%d0%be%d0%b8%d0%b7%d0%b2%d0%be%d0%b4%d1%81%d1%82%d0%b2%d0%be/" class="btn">Производство электроники</a>
					</div>
				</div>
				<div class="img">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/top-img.png' ); ?>" alt="Наши услуги">
				</div>
			</div>
		</div>
	</section>

	<section class="slogan-block">
		<div class="container">
			<div class="text-block">
				<div class="title">
					<h3>Наш принцип:</h3>
				</div>
				<div class="text">Ваша идея - наша реализация</div>
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
