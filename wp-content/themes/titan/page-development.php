<?php
/*
 * Template Name: Разработка
 */
get_header();

// Верхний блок
$h1         = get_field( 'top_title' ) ?: 'Разработка электроники';
$top_image  = get_field( 'top_bg_img' ) ?: get_template_directory_uri() . '/assets/img/production-top-img.png';
$top_desc   = get_field( 'top_description' ) ?: '<p>Наша компания предлагает услуги по разработке электронных устройств и встраиваемых систем: от идеи до готовой продукции.</p><p>Реализуем любой проект с нуля.</p>';

// Шаги
$steps = get_field( 'dev_steps' );

// Специализации
$spec_title = get_field( 'spec_title' ) ?: 'Мы специализируемся в следующих областях:';
$specs      = get_field( 'dev_specializations' );
$spec_image = get_field( 'spec_image' ) ?: get_template_directory_uri() . '/assets/img/development-top-img.jpg';

// Нижний блок
$form_title = get_field( 'form_title' ) ?: 'Свяжитесь с нами';
?>

<main class="inner-page development-page">
	<section class="top-block">
		<div class="container">
			<div class="top-block-inner grid">
				<div class="text-block">
					<div class="title"><h1><?php echo esc_html( $h1 ); ?></h1></div>
					<div class="text">
						<?php echo wp_kses_post( $top_desc ); ?>
					</div>
				</div>
				<div class="img"><img src="<?php echo esc_url( $top_image ); ?>" alt="<?php echo esc_attr( $h1 ); ?>"></div>
			</div>
		</div>
	</section>

	<section class="steps">
		<div class="container">
			<div class="steps-list grid">
				<?php if ( $steps ) : ?>
					<?php foreach ( $steps as $step ) : ?>
						<div class="item<?php echo ! empty( $step['step_is_last'] ) ? ' last' : ''; ?>">
							<?php if ( empty( $step['step_is_last'] ) && $step['step_number'] ) : ?>
								<div class="num"><?php echo esc_html( $step['step_number'] ); ?></div>
							<?php endif; ?>
							<div class="text"><?php echo esc_html( $step['step_text'] ); ?></div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="item"><div class="num">1</div><div class="text">Изучим концепцию</div></div>
					<div class="item"><div class="num">2</div><div class="text">Напишем Техническое задание</div></div>
					<div class="item"><div class="num">3</div><div class="text">Спроектируем и изготовим прототипы, напишем ПО</div></div>
					<div class="item"><div class="num">4</div><div class="text">Подготовим документацию согласно государственным стандартам</div></div>
					<div class="item"><div class="num">5</div><div class="text">Подготовим серийное производство</div></div>
					<div class="item last"><div class="text">Готовы включиться в работу с любого этапа</div></div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="list-block">
		<div class="container">
			<div class="list-block-inner grid">
				<div class="text-block">
					<div class="title"><h1><?php echo esc_html( $spec_title ); ?></h1></div>
					<div class="list">
						<ul>
							<?php if ( $specs ) : ?>
								<?php foreach ( $specs as $spec ) : ?>
									<li><?php echo esc_html( $spec['spec_text'] ); ?></li>
								<?php endforeach; ?>
							<?php else : ?>
								<li>Аналоговая и цифровая электроника;</li>
								<li>Радиоэлектронная аппаратура;</li>
								<li>Аналоговая и цифровая электроника;</li>
								<li>Радиоэлектронная аппаратура</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
				<div class="img"><img src="<?php echo esc_url( $spec_image ); ?>" alt="<?php echo esc_attr( $spec_title ); ?>"></div>
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
