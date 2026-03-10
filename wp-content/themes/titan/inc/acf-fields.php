<?php
/**
 * ACF Field Groups Registration
 *
 * Registers ACF field groups for Front Page, Production, and Development pages.
 * Uses acf_add_local_field_group() so fields work without importing JSON.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'titan_register_acf_fields' );

function titan_register_acf_fields() {

	// =========================================
	// Group 1: Front Page
	// =========================================
	acf_add_local_field_group( array(
		'key'      => 'group_titan_front_page',
		'title'    => 'Главная страница',
		'fields'   => array(
			array(
				'key'           => 'field_front_h1',
				'label'         => 'Заголовок H1',
				'name'          => 'front_h1',
				'type'          => 'text',
				'default_value' => 'Наши услуги:',
			),
			array(
				'key'        => 'field_front_services',
				'label'      => 'Услуги (список)',
				'name'       => 'front_services',
				'type'       => 'repeater',
				'layout'     => 'table',
				'min'        => 1,
				'sub_fields' => array(
					array(
						'key'   => 'field_front_service_text',
						'label' => 'Текст услуги',
						'name'  => 'service_text',
						'type'  => 'textarea',
						'rows'  => 2,
						'new_lines' => '',
					),
				),
			),
			array(
				'key'           => 'field_front_btn_dev_text',
				'label'         => 'Кнопка "Разработка" — текст',
				'name'          => 'front_btn_dev_text',
				'type'          => 'text',
				'default_value' => 'Разработка электроники',
			),
			array(
				'key'   => 'field_front_btn_dev_url',
				'label' => 'Кнопка "Разработка" — URL',
				'name'  => 'front_btn_dev_url',
				'type'  => 'url',
			),
			array(
				'key'           => 'field_front_btn_prod_text',
				'label'         => 'Кнопка "Производство" — текст',
				'name'          => 'front_btn_prod_text',
				'type'          => 'text',
				'default_value' => 'Производство электроники',
			),
			array(
				'key'   => 'field_front_btn_prod_url',
				'label' => 'Кнопка "Производство" — URL',
				'name'  => 'front_btn_prod_url',
				'type'  => 'url',
			),
			array(
				'key'           => 'field_front_top_image',
				'label'         => 'Изображение (верхний блок)',
				'name'          => 'front_top_image',
				'type'          => 'image',
				'return_format' => 'url',
			),
			array(
				'key'           => 'field_front_slogan_title',
				'label'         => 'Слоган — заголовок',
				'name'          => 'front_slogan_title',
				'type'          => 'text',
				'default_value' => 'Наш принцип:',
			),
			array(
				'key'           => 'field_front_slogan_text',
				'label'         => 'Слоган — текст',
				'name'          => 'front_slogan_text',
				'type'          => 'text',
				'default_value' => 'Ваша идея - наша реализация',
			),
			array(
				'key'           => 'field_front_form_title',
				'label'         => 'Форма — заголовок',
				'name'          => 'front_form_title',
				'type'          => 'text',
				'default_value' => 'Свяжитесь с нами',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
		'menu_order' => 0,
		'position'   => 'normal',
		'style'      => 'default',
		'active'     => true,
	) );

	// =========================================
	// Group 2: Production
	// =========================================
	acf_add_local_field_group( array(
		'key'      => 'group_titan_production',
		'title'    => 'Производство',
		'fields'   => array(
			array(
				'key'           => 'field_prod_h1',
				'label'         => 'Заголовок H1',
				'name'          => 'prod_h1',
				'type'          => 'text',
				'default_value' => 'Производство электроники',
			),
			array(
				'key'        => 'field_prod_features',
				'label'      => 'Преимущества (список)',
				'name'       => 'prod_features',
				'type'       => 'repeater',
				'layout'     => 'table',
				'min'        => 1,
				'sub_fields' => array(
					array(
						'key'   => 'field_prod_feature_text',
						'label' => 'Текст',
						'name'  => 'feature_text',
						'type'  => 'textarea',
						'rows'  => 2,
						'new_lines' => 'br',
					),
				),
			),
			array(
				'key'           => 'field_prod_top_image',
				'label'         => 'Изображение (верхний блок)',
				'name'          => 'prod_top_image',
				'type'          => 'image',
				'return_format' => 'url',
			),
			array(
				'key'           => 'field_prod_calc_title',
				'label'         => 'Калькулятор — заголовок',
				'name'          => 'prod_calc_title',
				'type'          => 'text',
				'default_value' => 'Для предварительного расчета воспользуйтесь калькулятором',
			),
			array(
				'key'           => 'field_prod_calc_smd_label',
				'label'         => 'Калькулятор — подпись SMD',
				'name'          => 'prod_calc_smd_label',
				'type'          => 'text',
				'default_value' => 'Количество точек пайки поверхностного монтажа (SMD)',
			),
			array(
				'key'           => 'field_prod_calc_tht_label',
				'label'         => 'Калькулятор — подпись THT',
				'name'          => 'prod_calc_tht_label',
				'type'          => 'text',
				'default_value' => 'Количество точек пайки выводного монтажа (ТНТ)',
			),
			array(
				'key'           => 'field_prod_calc_stencil_label',
				'label'         => 'Калькулятор — подпись "Трафарет"',
				'name'          => 'prod_calc_stencil_label',
				'type'          => 'text',
				'default_value' => 'Наличие трафарета',
			),
			array(
				'key'        => 'field_prod_calc_stencil_options',
				'label'      => 'Калькулятор — опции трафарета',
				'name'       => 'prod_calc_stencil_options',
				'type'       => 'repeater',
				'layout'     => 'table',
				'sub_fields' => array(
					array(
						'key'   => 'field_stencil_name',
						'label' => 'Название',
						'name'  => 'name',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_stencil_val_in',
						'label' => 'Коэффициент IN',
						'name'  => 'val_in',
						'type'  => 'number',
						'step'  => '0.01',
					),
					array(
						'key'   => 'field_stencil_val_out',
						'label' => 'Коэффициент OUT',
						'name'  => 'val_out',
						'type'  => 'number',
						'step'  => '0.01',
					),
				),
			),
			array(
				'key'           => 'field_prod_calc_components_label',
				'label'         => 'Калькулятор — подпись "Компоненты"',
				'name'          => 'prod_calc_components_label',
				'type'          => 'text',
				'default_value' => 'Электронные компоненты',
			),
			array(
				'key'        => 'field_prod_calc_components_options',
				'label'      => 'Калькулятор — опции компонентов',
				'name'       => 'prod_calc_components_options',
				'type'       => 'repeater',
				'layout'     => 'table',
				'sub_fields' => array(
					array(
						'key'   => 'field_comp_name',
						'label' => 'Название',
						'name'  => 'name',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_comp_css_class',
						'label' => 'CSS-класс',
						'name'  => 'css_class',
						'type'  => 'text',
					),
					array(
						'key'           => 'field_comp_overprice',
						'label'         => 'Overprice (data-overprice)',
						'name'          => 'overprice',
						'type'          => 'number',
						'step'          => '0.01',
						'default_value' => '',
					),
					array(
						'key'   => 'field_comp_wash',
						'label' => 'Wash (data-wash)',
						'name'  => 'wash',
						'type'  => 'number',
						'step'  => '1',
					),
					array(
						'key'   => 'field_comp_percent',
						'label' => 'Percent (data-percent)',
						'name'  => 'percent',
						'type'  => 'number',
						'step'  => '1',
					),
				),
			),
			array(
				'key'           => 'field_prod_calc_quantity_label',
				'label'         => 'Калькулятор — подпись "Количество"',
				'name'          => 'prod_calc_quantity_label',
				'type'          => 'text',
				'default_value' => 'Количество плат для монтажа',
			),
			array(
				'key'        => 'field_prod_calc_quantity_options',
				'label'      => 'Калькулятор — опции количества',
				'name'       => 'prod_calc_quantity_options',
				'type'       => 'repeater',
				'layout'     => 'table',
				'sub_fields' => array(
					array(
						'key'   => 'field_qty_name',
						'label' => 'Название',
						'name'  => 'name',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_qty_val',
						'label' => 'Коэффициент (data-val)',
						'name'  => 'val',
						'type'  => 'number',
						'step'  => '0.01',
					),
				),
			),
			array(
				'key'           => 'field_prod_calc_result_label',
				'label'         => 'Калькулятор — подпись результата',
				'name'          => 'prod_calc_result_label',
				'type'          => 'text',
				'default_value' => 'Стоимость мотажа одной платы',
			),
			array(
				'key'           => 'field_prod_calc_disclaimer',
				'label'         => 'Калькулятор — дисклеймер',
				'name'          => 'prod_calc_disclaimer',
				'type'          => 'textarea',
				'default_value' => 'Расчет является предварительным и не является Договором публичной оферты. Окончательную цену мы можем сказать после получения документации на изделие и обработки нашими специалистами',
				'rows'          => 3,
			),
			array(
				'key'           => 'field_prod_form_title',
				'label'         => 'Форма — заголовок',
				'name'          => 'prod_form_title',
				'type'          => 'text',
				'default_value' => 'Для получения окончательной цены свяжитесь с нами и приложите документацию',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-production.php',
				),
			),
		),
		'menu_order' => 0,
		'position'   => 'normal',
		'style'      => 'default',
		'active'     => true,
	) );

	// =========================================
	// Group 3: Development
	// =========================================
	acf_add_local_field_group( array(
		'key'      => 'group_titan_development',
		'title'    => 'Разработка',
		'fields'   => array(
			array(
				'key'           => 'field_dev_h1',
				'label'         => 'Заголовок H1',
				'name'          => 'dev_h1',
				'type'          => 'text',
				'default_value' => 'Разработка электроники',
			),
			array(
				'key'           => 'field_dev_intro_text',
				'label'         => 'Вводный текст',
				'name'          => 'dev_intro_text',
				'type'          => 'wysiwyg',
				'tabs'          => 'all',
				'toolbar'       => 'basic',
				'media_upload'  => 0,
				'default_value' => '<p>Наша компания предлагает услуги по разработке электронных устройств и встраиваемых систем: от идеи до готовой продукции.</p><p>Реализуем любой проект с нуля.</p>',
			),
			array(
				'key'           => 'field_dev_top_image',
				'label'         => 'Изображение (верхний блок)',
				'name'          => 'dev_top_image',
				'type'          => 'image',
				'return_format' => 'url',
			),
			array(
				'key'        => 'field_dev_steps',
				'label'      => 'Шаги',
				'name'       => 'dev_steps',
				'type'       => 'repeater',
				'layout'     => 'table',
				'sub_fields' => array(
					array(
						'key'   => 'field_step_number',
						'label' => 'Номер',
						'name'  => 'step_number',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_step_text',
						'label' => 'Текст',
						'name'  => 'step_text',
						'type'  => 'text',
					),
					array(
						'key'           => 'field_step_is_last',
						'label'         => 'Последний (без номера)',
						'name'          => 'step_is_last',
						'type'          => 'true_false',
						'default_value' => 0,
						'ui'            => 1,
					),
				),
			),
			array(
				'key'           => 'field_dev_spec_title',
				'label'         => 'Специализации — заголовок',
				'name'          => 'dev_spec_title',
				'type'          => 'text',
				'default_value' => 'Мы специализируемся в следующих областях:',
			),
			array(
				'key'        => 'field_dev_specializations',
				'label'      => 'Специализации (список)',
				'name'       => 'dev_specializations',
				'type'       => 'repeater',
				'layout'     => 'table',
				'sub_fields' => array(
					array(
						'key'   => 'field_spec_text',
						'label' => 'Текст',
						'name'  => 'spec_text',
						'type'  => 'text',
					),
				),
			),
			array(
				'key'           => 'field_dev_spec_image',
				'label'         => 'Специализации — изображение',
				'name'          => 'dev_spec_image',
				'type'          => 'image',
				'return_format' => 'url',
			),
			array(
				'key'           => 'field_dev_form_title',
				'label'         => 'Форма — заголовок',
				'name'          => 'dev_form_title',
				'type'          => 'text',
				'default_value' => 'Свяжитесь с нами',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page-development.php',
				),
			),
		),
		'menu_order' => 0,
		'position'   => 'normal',
		'style'      => 'default',
		'active'     => true,
	) );
}

/**
 * ACF Local JSON: save path
 */
add_filter( 'acf/settings/save_json', 'titan_acf_json_save_point' );
function titan_acf_json_save_point( $path ) {
	return get_stylesheet_directory() . '/acf-json';
}

/**
 * ACF Local JSON: load path
 */
add_filter( 'acf/settings/load_json', 'titan_acf_json_load_point' );
function titan_acf_json_load_point( $paths ) {
	unset( $paths[0] );
	$paths[] = get_stylesheet_directory() . '/acf-json';
	return $paths;
}
