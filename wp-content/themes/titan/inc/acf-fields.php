<?php
/**
 * ACF Field Groups — импорт в БД
 *
 * Группа "Главная страница" (group_698079284b737) уже существует в БД.
 * Здесь импортируются группы "Производство" и "Разработка" в БД через
 * acf_import_field_group() — они появляются в ACF → Группы полей
 * и редактируются через интерфейс.
 *
 * Импорт выполняется однократно (по опции titan_acf_groups_v1).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'titan_import_acf_field_groups' );

function titan_import_acf_field_groups() {

	if ( get_option( 'titan_acf_groups_v1' ) ) {
		return;
	}

	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	// =========================================
	// Производство
	// =========================================
	acf_import_field_group( array(
		'key'                   => 'group_titan_production',
		'title'                 => 'Производство',
		'fields'                => array(

			// --- Tab: Верхний блок ---
			array(
				'key'       => 'field_prod_tab_top',
				'label'     => 'Верхний блок',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
				'endpoint'  => 0,
			),
			array(
				'key'     => 'field_prod_h1',
				'label'   => 'Заголовок',
				'name'    => 'top_title',
				'type'    => 'text',
				'wrapper' => array( 'width' => '75', 'class' => '', 'id' => '' ),
			),
			array(
				'key'           => 'field_prod_top_image',
				'label'         => 'Фоновое изображение',
				'name'          => 'top_bg_img',
				'type'          => 'image',
				'return_format' => 'url',
				'preview_size'  => 'full',
				'library'       => 'all',
				'wrapper'       => array( 'width' => '25', 'class' => '', 'id' => '' ),
			),
			array(
				'key'          => 'field_prod_description',
				'label'        => 'Описание',
				'name'         => 'top_description',
				'type'         => 'wysiwyg',
				'tabs'         => 'all',
				'toolbar'      => 'full',
				'media_upload' => 1,
				'delay'        => 0,
			),

			// --- Tab: Калькулятор ---
			array(
				'key'       => 'field_prod_tab_calc',
				'label'     => 'Калькулятор',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
				'endpoint'  => 0,
			),
			array(
				'key'   => 'field_prod_calc_title',
				'label' => 'Заголовок калькулятора',
				'name'  => 'calc_title',
				'type'  => 'text',
			),
			array(
				'key'     => 'field_prod_calc_smd_label',
				'label'   => 'Подпись: SMD',
				'name'    => 'calc_smd_label',
				'type'    => 'text',
				'wrapper' => array( 'width' => '50', 'class' => '', 'id' => '' ),
			),
			array(
				'key'     => 'field_prod_calc_tht_label',
				'label'   => 'Подпись: THT',
				'name'    => 'calc_tht_label',
				'type'    => 'text',
				'wrapper' => array( 'width' => '50', 'class' => '', 'id' => '' ),
			),
			array(
				'key'     => 'field_prod_calc_stencil_label',
				'label'   => 'Подпись: Трафарет',
				'name'    => 'calc_stencil_label',
				'type'    => 'text',
				'wrapper' => array( 'width' => '50', 'class' => '', 'id' => '' ),
			),
			array(
				'key'        => 'field_prod_calc_stencil_options',
				'label'      => 'Опции трафарета',
				'name'       => 'calc_stencil_options',
				'type'       => 'repeater',
				'layout'     => 'table',
				'wrapper'    => array( 'width' => '50', 'class' => '', 'id' => '' ),
				'sub_fields' => array(
					array(
						'key'   => 'field_stencil_name',
						'label' => 'Название',
						'name'  => 'name',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_stencil_val_in',
						'label' => 'Коэфф. IN',
						'name'  => 'val_in',
						'type'  => 'number',
						'step'  => '0.01',
					),
					array(
						'key'   => 'field_stencil_val_out',
						'label' => 'Коэфф. OUT',
						'name'  => 'val_out',
						'type'  => 'number',
						'step'  => '0.01',
					),
				),
			),
			array(
				'key'     => 'field_prod_calc_comp_label',
				'label'   => 'Подпись: Компоненты',
				'name'    => 'calc_components_label',
				'type'    => 'text',
				'wrapper' => array( 'width' => '50', 'class' => '', 'id' => '' ),
			),
			array(
				'key'        => 'field_prod_calc_comp_options',
				'label'      => 'Опции компонентов',
				'name'       => 'calc_components_options',
				'type'       => 'repeater',
				'layout'     => 'table',
				'wrapper'    => array( 'width' => '50', 'class' => '', 'id' => '' ),
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
						'key'   => 'field_comp_overprice',
						'label' => 'Overprice',
						'name'  => 'overprice',
						'type'  => 'number',
						'step'  => '0.01',
					),
					array(
						'key'   => 'field_comp_wash',
						'label' => 'Wash',
						'name'  => 'wash',
						'type'  => 'number',
					),
					array(
						'key'   => 'field_comp_percent',
						'label' => 'Percent',
						'name'  => 'percent',
						'type'  => 'number',
					),
				),
			),
			array(
				'key'     => 'field_prod_calc_qty_label',
				'label'   => 'Подпись: Количество плат',
				'name'    => 'calc_quantity_label',
				'type'    => 'text',
				'wrapper' => array( 'width' => '50', 'class' => '', 'id' => '' ),
			),
			array(
				'key'        => 'field_prod_calc_qty_options',
				'label'      => 'Опции количества',
				'name'       => 'calc_quantity_options',
				'type'       => 'repeater',
				'layout'     => 'table',
				'wrapper'    => array( 'width' => '50', 'class' => '', 'id' => '' ),
				'sub_fields' => array(
					array(
						'key'   => 'field_qty_name',
						'label' => 'Название',
						'name'  => 'name',
						'type'  => 'text',
					),
					array(
						'key'   => 'field_qty_val',
						'label' => 'Коэффициент',
						'name'  => 'val',
						'type'  => 'number',
						'step'  => '0.01',
					),
				),
			),
			array(
				'key'     => 'field_prod_calc_result_label',
				'label'   => 'Подпись результата',
				'name'    => 'calc_result_label',
				'type'    => 'text',
				'wrapper' => array( 'width' => '50', 'class' => '', 'id' => '' ),
			),
			array(
				'key'     => 'field_prod_calc_disclaimer',
				'label'   => 'Дисклеймер',
				'name'    => 'calc_disclaimer',
				'type'    => 'textarea',
				'rows'    => 3,
				'wrapper' => array( 'width' => '50', 'class' => '', 'id' => '' ),
			),

			// --- Tab: Нижний блок ---
			array(
				'key'       => 'field_prod_tab_bottom',
				'label'     => 'Нижний блок',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
				'endpoint'  => 0,
			),
			array(
				'key'   => 'field_prod_form_title',
				'label' => 'Заголовок формы',
				'name'  => 'form_title',
				'type'  => 'text',
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
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
	) );

	// =========================================
	// Разработка
	// =========================================
	acf_import_field_group( array(
		'key'                   => 'group_titan_development',
		'title'                 => 'Разработка',
		'fields'                => array(

			// --- Tab: Верхний блок ---
			array(
				'key'       => 'field_dev_tab_top',
				'label'     => 'Верхний блок',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
				'endpoint'  => 0,
			),
			array(
				'key'     => 'field_dev_h1',
				'label'   => 'Заголовок',
				'name'    => 'top_title',
				'type'    => 'text',
				'wrapper' => array( 'width' => '75', 'class' => '', 'id' => '' ),
			),
			array(
				'key'           => 'field_dev_top_image',
				'label'         => 'Фоновое изображение',
				'name'          => 'top_bg_img',
				'type'          => 'image',
				'return_format' => 'url',
				'preview_size'  => 'full',
				'library'       => 'all',
				'wrapper'       => array( 'width' => '25', 'class' => '', 'id' => '' ),
			),
			array(
				'key'          => 'field_dev_intro_text',
				'label'        => 'Описание',
				'name'         => 'top_description',
				'type'         => 'wysiwyg',
				'tabs'         => 'all',
				'toolbar'      => 'full',
				'media_upload' => 1,
				'delay'        => 0,
			),

			// --- Tab: Шаги ---
			array(
				'key'       => 'field_dev_tab_steps',
				'label'     => 'Шаги',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
				'endpoint'  => 0,
			),
			array(
				'key'        => 'field_dev_steps',
				'label'      => 'Список шагов',
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

			// --- Tab: Специализации ---
			array(
				'key'       => 'field_dev_tab_specs',
				'label'     => 'Специализации',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
				'endpoint'  => 0,
			),
			array(
				'key'     => 'field_dev_spec_title',
				'label'   => 'Заголовок',
				'name'    => 'spec_title',
				'type'    => 'text',
				'wrapper' => array( 'width' => '75', 'class' => '', 'id' => '' ),
			),
			array(
				'key'           => 'field_dev_spec_image',
				'label'         => 'Изображение',
				'name'          => 'spec_image',
				'type'          => 'image',
				'return_format' => 'url',
				'preview_size'  => 'full',
				'library'       => 'all',
				'wrapper'       => array( 'width' => '25', 'class' => '', 'id' => '' ),
			),
			array(
				'key'        => 'field_dev_specializations',
				'label'      => 'Список специализаций',
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

			// --- Tab: Нижний блок ---
			array(
				'key'       => 'field_dev_tab_bottom',
				'label'     => 'Нижний блок',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
				'endpoint'  => 0,
			),
			array(
				'key'   => 'field_dev_form_title',
				'label' => 'Заголовок формы',
				'name'  => 'form_title',
				'type'  => 'text',
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
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
	) );

	update_option( 'titan_acf_groups_v1', true );
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
