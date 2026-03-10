<?php
/*
 * Template Name: Производство
 */
get_header();

$h1              = get_field( 'prod_h1' ) ?: 'Производство электроники';
$features        = get_field( 'prod_features' );
$top_image       = get_field( 'prod_top_image' ) ?: get_template_directory_uri() . '/assets/img/production-top-img.png';
$calc_title      = get_field( 'prod_calc_title' ) ?: 'Для предварительного расчета воспользуйтесь калькулятором';
$smd_label       = get_field( 'prod_calc_smd_label' ) ?: 'Количество точек пайки поверхностного монтажа (SMD)';
$tht_label       = get_field( 'prod_calc_tht_label' ) ?: 'Количество точек пайки выводного монтажа (ТНТ)';
$stencil_label   = get_field( 'prod_calc_stencil_label' ) ?: 'Наличие трафарета';
$stencil_options = get_field( 'prod_calc_stencil_options' );
$comp_label      = get_field( 'prod_calc_components_label' ) ?: 'Электронные компоненты';
$comp_options    = get_field( 'prod_calc_components_options' );
$qty_label       = get_field( 'prod_calc_quantity_label' ) ?: 'Количество плат для монтажа';
$qty_options     = get_field( 'prod_calc_quantity_options' );
$result_label    = get_field( 'prod_calc_result_label' ) ?: 'Стоимость мотажа одной платы';
$disclaimer      = get_field( 'prod_calc_disclaimer' ) ?: 'Расчет является предварительным и не является Договором публичной оферты. Окончательную цену мы можем сказать после получения документации на изделие и обработки нашими специалистами';
$form_title      = get_field( 'prod_form_title' ) ?: 'Для получения окончательной цены свяжитесь с нами и приложите документацию';
?>

<main class="inner-page production-page">
	<section class="top-block">
		<div class="container">
			<div class="top-block-inner grid">
				<div class="text-block">
					<div class="title"><h1><?php echo esc_html( $h1 ); ?></h1></div>
					<div class="list">
						<ul>
							<?php if ( $features ) : ?>
								<?php foreach ( $features as $feature ) : ?>
									<li><?php echo wp_kses_post( $feature['feature_text'] ); ?></li>
								<?php endforeach; ?>
							<?php else : ?>
								<li>Изготовление печатных плат от 3 до 5 класса точности;</li>
								<li>Возможность срочного изготовления;</li>
								<li>Собственное производство;</li>
								<li>Поверхностный (SMT/SMD) и выводной (THT) монтаж <br>электронных компонентов от одной платы<br> до крупной серии</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
				<div class="img"><img src="<?php echo esc_url( $top_image ); ?>" alt="<?php echo esc_attr( $h1 ); ?>"></div>
			</div>
		</div>
	</section>

	<section class="calculator">
		<div class="container">
			<div class="grid">
				<div class="title">
					<h3><?php echo esc_html( $calc_title ); ?></h3>
				</div>
				<div class="form-block calc-form">
					<form action="/" method="post">
						<div class="form-inner">
							<div class="form-field">
								<div class="caption"><?php echo esc_html( $smd_label ); ?></div>
								<input type="number" class="indot" placeholder="<?php echo esc_attr( $smd_label ); ?>">
							</div>
							<div class="form-field">
								<div class="caption"><?php echo esc_html( $tht_label ); ?></div>
								<input type="number" class="outdot" placeholder="<?php echo esc_attr( $tht_label ); ?>">
							</div>
							<div class="select-field">
								<div class="caption"><?php echo esc_html( $stencil_label ); ?></div>
								<div class="select">
									<div class="current"><?php echo esc_html( $stencil_label ); ?></div>
									<div class="list">
										<?php if ( $stencil_options ) : ?>
											<?php foreach ( $stencil_options as $opt ) : ?>
												<a href="#" class="option trafaret" data-valIn="<?php echo esc_attr( $opt['val_in'] ); ?>" data-valOut="<?php echo esc_attr( $opt['val_out'] ); ?>"><?php echo esc_html( $opt['name'] ); ?></a>
											<?php endforeach; ?>
										<?php else : ?>
											<a href="#" class="option trafaret" data-valIn="1.25" data-valOut="2.8">С трафаретом</a>
											<a href="#" class="option trafaret" data-valIn="1.65" data-valOut="2.8">Без трафарета</a>
										<?php endif; ?>
									</div>
								</div>
								<input type="hidden" name="trafaret-sum">
							</div>
							<div class="select-field">
								<div class="caption"><?php echo esc_html( $comp_label ); ?></div>
								<div class="select">
									<div class="current"><?php echo esc_html( $comp_label ); ?></div>
									<div class="list">
										<?php if ( $comp_options ) : ?>
											<?php foreach ( $comp_options as $opt ) : ?>
												<a href="#" class="option <?php echo esc_attr( $opt['css_class'] ); ?>"<?php if ( $opt['overprice'] ) : ?> data-overprice="<?php echo esc_attr( $opt['overprice'] ); ?>"<?php endif; ?> data-wash="<?php echo esc_attr( $opt['wash'] ); ?>" data-percent="<?php echo esc_attr( $opt['percent'] ); ?>"><?php echo esc_html( $opt['name'] ); ?></a>
											<?php endforeach; ?>
										<?php else : ?>
											<a href="#" class="option component1 components" data-overprice="1.25" data-wash="46" data-percent="10">Заказчика</a>
											<a href="#" class="option component2 components" data-wash="46" data-percent="10">Исполнителя</a>
										<?php endif; ?>
									</div>
								</div>
								<input type="hidden" name="components-sum">
							</div>
							<div class="select-field">
								<div class="caption"><?php echo esc_html( $qty_label ); ?></div>
								<div class="select">
									<div class="current"><?php echo esc_html( $qty_label ); ?></div>
									<div class="list">
										<?php if ( $qty_options ) : ?>
											<?php foreach ( $qty_options as $opt ) : ?>
												<a href="#" class="option quantity" data-val="<?php echo esc_attr( $opt['val'] ); ?>"><?php echo esc_html( $opt['name'] ); ?></a>
											<?php endforeach; ?>
										<?php else : ?>
											<a href="#" class="option quantity" data-val="2">1..9</a>
											<a href="#" class="option quantity" data-val="1.5">10..49</a>
											<a href="#" class="option quantity" data-val="1.25">50..99</a>
											<a href="#" class="option quantity" data-val="1">100..</a>
										<?php endif; ?>
									</div>
								</div>
								<input type="hidden" name="quantity-sum">
							</div>
							<div class="result-block">
								<div class="caption"><?php echo esc_html( $result_label ); ?></div>
								<div class="val"><span>0</span> ₽</div>
							</div>
							<div class="text"><span>*</span><?php echo esc_html( $disclaimer ); ?></div>
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
