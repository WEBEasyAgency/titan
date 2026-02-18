<?php
/*
 * Template Name: Производство
 */
get_header(); ?>

<main class="inner-page production-page">
	<section class="top-block">
		<div class="container">
			<div class="top-block-inner grid">
				<div class="text-block">
					<div class="title"><h1>Производство электроники</h1></div>
					<div class="list">
						<ul>
							<li>Изготовление печатных плат от 3 до 5 класса точности;</li>
							<li>Возможность срочного изготовления;</li>
							<li>Собственное производство;</li>
							<li>Поверхностный (SMT/SMD) и выводной (THT) монтаж <br>электронных компонентов от одной платы<br> до крупной серии</li>
						</ul>
					</div>
				</div>
				<div class="img"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/production-top-img.png' ); ?>" alt="Производство электроники"></div>
			</div>
		</div>
	</section>

	<section class="calculator">
		<div class="container">
			<div class="grid">
				<div class="title">
					<h3>Для предварительного расчета воспользуйтесь калькулятором</h3>
				</div>
				<div class="form-block calc-form">
					<form action="/" method="post">
						<div class="form-inner">
							<div class="form-field">
								<div class="caption">Количество точек пайки поверхностного монтажа (SMD)</div>
								<input type="number" class="indot" placeholder="Количество точек пайки поверхностного монтажа (SMD)">
							</div>
							<div class="form-field">
								<div class="caption">Количество точек пайки выводного монтажа (ТНТ)</div>
								<input type="number" class="outdot" placeholder="Количество точек пайки выводного монтажа (ТНТ)">
							</div>
							<div class="select-field">
								<div class="caption">Наличие трафарета</div>
								<div class="select">
									<div class="current">Наличие трафарета</div>
									<div class="list">
										<a href="#" class="option trafaret" data-valIn="1.25" data-valOut="2.8">С трафаретом</a>
										<a href="#" class="option trafaret" data-valIn="1.65" data-valOut="2.8">Без трафарета</a>
									</div>
								</div>
								<input type="hidden" name="trafaret-sum">
							</div>
							<div class="select-field">
								<div class="caption">Электронные компоненты</div>
								<div class="select">
									<div class="current">Электронные компоненты</div>
									<div class="list">
										<a href="#" class="option component1 components" data-percent="10">Заказчика</a>
										<a href="#" class="option component2 components" data-overprice="1.25" data-wash="46" data-percent="10">Исполнителя</a>
									</div>
								</div>
								<input type="hidden" name="components-sum">
							</div>
							<div class="select-field">
								<div class="caption">Количество плат для монтажа</div>
								<div class="select">
									<div class="current">Количество плат для монтажа</div>
									<div class="list">
										<a href="#" class="option quantity" data-val="2">1..9</a>
										<a href="#" class="option quantity" data-val="1.5">10..49</a>
										<a href="#" class="option quantity" data-val="1.25">50..99</a>
										<a href="#" class="option quantity" data-val="1">100..</a>
									</div>
								</div>
								<input type="hidden" name="quantity-sum">
							</div>
							<div class="result-block">
								<div class="caption">Стоимость мотажа одной платы</div>
								<div class="val"><span>0</span> ₽</div>
							</div>
							<div class="text"><span>*</span>Расчет является предварительным и не является Договором публичной оферты. Окончательную цену мы можем сказать после получения документации на изделие и обработки нашими специалистами</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<section class="contacts-form">
		<div class="container">
			<div class="grid">
				<div class="title">
					<h3>Для получения окончательной цены свяжитесь с нами и приложите документацию</h3>
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
