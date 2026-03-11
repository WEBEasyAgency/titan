<?php
/**
 * ACF Demo Data Import
 *
 * Заполняет ACF-поля контентом из вёрстки при первом запуске.
 * Выполняется однократно, контролируется опцией titan_acf_demo_v2.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'titan_acf_import_demo_data', 20 );

function titan_acf_import_demo_data() {
	if ( get_option( 'titan_acf_demo_v2' ) ) {
		return;
	}

	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	// =========================================
	// Главная страница
	// (группа group_698079284b737 уже в БД)
	// =========================================
	$front_page_id = get_option( 'page_on_front' );
	if ( $front_page_id ) {
		update_field( 'top_title', 'Наши услуги:', $front_page_id );
		update_field( 'top_description', '<ul>
<li>разработка электронных устройств и встраиваемых систем;</li>
<li>контрактное производство электроники.</li>
</ul>', $front_page_id );
		update_field( 'medium_title', 'Наш принцип:', $front_page_id );
		update_field( 'medium_description', 'Ваша идея - наша реализация', $front_page_id );
	}

	// =========================================
	// Производство
	// =========================================
	$prod_page_id = titan_get_page_by_template( 'page-production.php' );
	if ( $prod_page_id ) {
		update_field( 'top_title', 'Производство электроники', $prod_page_id );
		update_field( 'top_description', '<ul>
<li>Изготовление печатных плат от 3 до 5 класса точности;</li>
<li>Возможность срочного изготовления;</li>
<li>Собственное производство;</li>
<li>Поверхностный (SMT/SMD) и выводной (THT) монтаж электронных компонентов от одной платы до крупной серии</li>
</ul>', $prod_page_id );

		update_field( 'calc_title', 'Для предварительного расчета воспользуйтесь калькулятором', $prod_page_id );
		update_field( 'calc_smd_label', 'Количество точек пайки поверхностного монтажа (SMD)', $prod_page_id );
		update_field( 'calc_tht_label', 'Количество точек пайки выводного монтажа (ТНТ)', $prod_page_id );
		update_field( 'calc_stencil_label', 'Наличие трафарета', $prod_page_id );

		update_field( 'calc_stencil_options', array(
			array( 'name' => 'С трафаретом', 'val_in' => 1.25, 'val_out' => 2.8 ),
			array( 'name' => 'Без трафарета', 'val_in' => 1.65, 'val_out' => 2.8 ),
		), $prod_page_id );

		update_field( 'calc_components_label', 'Электронные компоненты', $prod_page_id );

		update_field( 'calc_components_options', array(
			array( 'name' => 'Заказчика', 'css_class' => 'component1 components', 'overprice' => 1.25, 'wash' => 46, 'percent' => 10 ),
			array( 'name' => 'Исполнителя', 'css_class' => 'component2 components', 'overprice' => '', 'wash' => 46, 'percent' => 10 ),
		), $prod_page_id );

		update_field( 'calc_quantity_label', 'Количество плат для монтажа', $prod_page_id );

		update_field( 'calc_quantity_options', array(
			array( 'name' => '1..9', 'val' => 2 ),
			array( 'name' => '10..49', 'val' => 1.5 ),
			array( 'name' => '50..99', 'val' => 1.25 ),
			array( 'name' => '100..', 'val' => 1 ),
		), $prod_page_id );

		update_field( 'calc_result_label', 'Стоимость мотажа одной платы', $prod_page_id );
		update_field( 'calc_disclaimer', 'Расчет является предварительным и не является Договором публичной оферты. Окончательную цену мы можем сказать после получения документации на изделие и обработки нашими специалистами', $prod_page_id );
		update_field( 'form_title', 'Для получения окончательной цены свяжитесь с нами и приложите документацию', $prod_page_id );
	}

	// =========================================
	// Разработка
	// =========================================
	$dev_page_id = titan_get_page_by_template( 'page-development.php' );
	if ( $dev_page_id ) {
		update_field( 'top_title', 'Разработка электроники', $dev_page_id );
		update_field( 'top_description', '<p>Наша компания предлагает услуги по разработке электронных устройств и встраиваемых систем: от идеи до готовой продукции.</p><p>Реализуем любой проект с нуля.</p>', $dev_page_id );

		update_field( 'dev_steps', array(
			array( 'step_number' => '1', 'step_text' => 'Изучим концепцию', 'step_is_last' => 0 ),
			array( 'step_number' => '2', 'step_text' => 'Напишем Техническое задание', 'step_is_last' => 0 ),
			array( 'step_number' => '3', 'step_text' => 'Спроектируем и изготовим прототипы, напишем ПО', 'step_is_last' => 0 ),
			array( 'step_number' => '4', 'step_text' => 'Подготовим документацию согласно государственным стандартам', 'step_is_last' => 0 ),
			array( 'step_number' => '5', 'step_text' => 'Подготовим серийное производство', 'step_is_last' => 0 ),
			array( 'step_number' => '',  'step_text' => 'Готовы включиться в работу с любого этапа', 'step_is_last' => 1 ),
		), $dev_page_id );

		update_field( 'spec_title', 'Мы специализируемся в следующих областях:', $dev_page_id );

		update_field( 'dev_specializations', array(
			array( 'spec_text' => 'Аналоговая и цифровая электроника;' ),
			array( 'spec_text' => 'Радиоэлектронная аппаратура;' ),
			array( 'spec_text' => 'Аналоговая и цифровая электроника;' ),
			array( 'spec_text' => 'Радиоэлектронная аппаратура' ),
		), $dev_page_id );

		update_field( 'form_title', 'Свяжитесь с нами', $dev_page_id );
	}

	update_option( 'titan_acf_demo_v2', true );
}

/**
 * ACF Demo Data: Контакты (v3)
 */
add_action( 'acf/init', 'titan_acf_import_contacts_demo', 20 );

function titan_acf_import_contacts_demo() {
	if ( get_option( 'titan_acf_demo_contacts_v1' ) ) {
		return;
	}

	if ( ! function_exists( 'update_field' ) || ! function_exists( 'titan_get_page_by_template' ) ) {
		return;
	}

	$contacts_page_id = titan_get_page_by_template( 'page-contacts.php' );
	if ( ! $contacts_page_id ) {
		return;
	}

	update_field( 'contacts_title', 'Контакты', $contacts_page_id );
	update_field( 'contacts_address', 'ул. Дружбы 5–40, г. Альметьевск, респ. Татарстан, Россия, 423453', $contacts_page_id );
	update_field( 'contacts_phone', '+7 (495) 970-26-98', $contacts_page_id );
	update_field( 'contacts_phone_raw', '+74959702698', $contacts_page_id );
	update_field( 'contacts_email', 'info@titan-project.ru', $contacts_page_id );

	update_field( 'contacts_requisites', array(
		array( 'text' => '<p>ООО «Титан-Проджект»<br>ИНН: 1644095518<br>КПП: 164401001</p>' ),
		array( 'text' => '<p>ИП Ахмадиева Ольга Анатольевна<br>ИНН: 164406464347<br>Свидетельство о регистрации: 16 №006219266 от 11.10.2011</p>' ),
	), $contacts_page_id );

	update_field( 'contacts_form_title', 'Свяжитесь с нами', $contacts_page_id );

	update_option( 'titan_acf_demo_contacts_v1', true );
}

/**
 * Helper: find page ID by template.
 */
if ( ! function_exists( 'titan_get_page_by_template' ) ) {
	function titan_get_page_by_template( $template ) {
		$pages = get_posts( array(
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => $template,
			'fields'         => 'ids',
		) );
		return ! empty( $pages ) ? $pages[0] : 0;
	}
}
