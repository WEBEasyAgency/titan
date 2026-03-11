<?php
/**
 * Custom Post Type: Проекты (titan_project)
 *
 * Регистрация CPT, ACF-группа полей, AJAX load more, демо-контент.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =========================================
// 1. Register CPT
// =========================================
add_action( 'init', 'titan_register_cpt_project' );

function titan_register_cpt_project() {
	register_post_type( 'titan_project', array(
		'labels' => array(
			'name'               => 'Проекты',
			'singular_name'      => 'Проект',
			'add_new'            => 'Добавить проект',
			'add_new_item'       => 'Новый проект',
			'edit_item'          => 'Редактировать проект',
			'view_item'          => 'Смотреть проект',
			'all_items'          => 'Все проекты',
			'search_items'       => 'Поиск проектов',
			'not_found'          => 'Проектов не найдено',
			'not_found_in_trash' => 'В корзине пусто',
			'menu_name'          => 'Проекты',
		),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-portfolio',
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'has_archive'         => true,
		'rewrite'             => array( 'slug' => 'projects', 'with_front' => false ),
		'show_in_rest'        => false,
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
	) );
}

// =========================================
// 2. ACF Field Group
// =========================================
add_action( 'acf/init', 'titan_import_acf_projects_group' );

function titan_import_acf_projects_group() {

	if ( get_option( 'titan_acf_groups_projects_v1' ) ) {
		return;
	}

	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	acf_import_field_group( array(
		'key'                   => 'group_titan_project',
		'title'                 => 'Проект',
		'fields'                => array(

			// --- Tab: Карточка ---
			array(
				'key'       => 'field_project_tab_card',
				'label'     => 'Карточка',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
				'endpoint'  => 0,
			),
			array(
				'key'          => 'field_project_short_desc',
				'label'        => 'Краткое описание',
				'name'         => 'project_short_desc',
				'type'         => 'textarea',
				'rows'         => 4,
				'new_lines'    => 'wpautop',
				'instructions' => 'Отображается в карточке проекта на странице списка',
			),

			// --- Tab: Детальная страница ---
			array(
				'key'       => 'field_project_tab_detail',
				'label'     => 'Детальная страница',
				'name'      => '',
				'type'      => 'tab',
				'placement' => 'top',
				'endpoint'  => 0,
			),
			array(
				'key'           => 'field_project_top_image',
				'label'         => 'Верхнее изображение',
				'name'          => 'project_top_image',
				'type'          => 'image',
				'return_format' => 'url',
				'preview_size'  => 'medium',
				'library'       => 'all',
				'instructions'  => 'Широкое изображение в шапке детальной страницы',
			),
			array(
				'key'           => 'field_project_main_image',
				'label'         => 'Основное изображение',
				'name'          => 'project_main_image',
				'type'          => 'image',
				'return_format' => 'url',
				'preview_size'  => 'medium',
				'library'       => 'all',
				'instructions'  => 'Изображение справа от текста',
			),
			array(
				'key'           => 'field_project_gallery',
				'label'         => 'Слайдер изображений',
				'name'          => 'project_gallery',
				'type'          => 'gallery',
				'return_format' => 'url',
				'preview_size'  => 'thumbnail',
				'library'       => 'all',
				'instructions'  => 'Изображения для слайдера под основным изображением',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'titan_project',
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

	update_option( 'titan_acf_groups_projects_v1', true );
}

// =========================================
// 3. AJAX: Load More Projects
// =========================================
add_action( 'wp_ajax_titan_load_more_projects', 'titan_ajax_load_more_projects' );
add_action( 'wp_ajax_nopriv_titan_load_more_projects', 'titan_ajax_load_more_projects' );

function titan_ajax_load_more_projects() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );

	$page     = intval( $_POST['page'] ?? 1 );
	$per_page = 3;

	$query = new WP_Query( array(
		'post_type'      => 'titan_project',
		'posts_per_page' => $per_page,
		'paged'          => $page,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );

	if ( $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/project', 'card' );
		}
		wp_reset_postdata();

		wp_send_json_success( array(
			'html'     => ob_get_clean(),
			'has_more' => $page < $query->max_num_pages,
		) );
	} else {
		wp_send_json_success( array( 'html' => '', 'has_more' => false ) );
	}
}

// =========================================
// 4. Demo Content Import
// =========================================
add_action( 'init', 'titan_import_demo_projects', 20 );

function titan_import_demo_projects() {
	if ( get_option( 'titan_projects_demo_v1' ) ) {
		return;
	}

	// Wait for ACF
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$theme_img_url = get_template_directory_uri() . '/assets/img';

	$projects = array(
		array(
			'title'      => 'Разработка электроники',
			'short_desc' => '<p>Наша компания предлагает услуги по разработке электронных устройств и встраиваемых систем: от идеи до готовой продукции.</p><p>Реализуем любой проект с нуля.</p>',
			'content'    => '<h3>Разработка электроники</h3>
<p>Микросхема — это небольшое электронное устройство, состоящее из различных радиоэлементов и помещённое в неразборный корпус. Зачем она нужна? По сути, это «кирпичик», благодаря которому происходит миниатюризация проблем для работоспособности электроники.</p>
<p>С помощью микросхемы можно выполнять типовые задачи. Это возможно благодаря множеству компонентов: резисторов, транзисторов, конденсаторов, диодов. При этом производится преобразование различных типов сигналов (аналоговых, цифровых) за счёт проведения электрических импульсов по цепи, их усиления и переключения. Выглядит это как своеобразная сеть дорог: сигнал идёт по одному пути, потом сворачивает или размножается, преобразовывается и в конце приходит в заданную точку либо исчезает. При этом каждый контакт на микросхеме — это вывод, который может быть как соединением с другим устройством, так и служить заземлением, питанием, обеспечивать ввод данных.</p>
<p>Рассмотрим принцип работы микросхемы, разберемся в различиях основных типов, узнаем, какие компоненты нужны для работы.</p>
<h3>Компоненты микросхем</h3>
<p>Простыми словами микросхема — это набор компонентов, которые выполняют различные функции, при этом они объединены в общую схему. В микросхему входят резисторы, транзисторы, конденсаторы, контакты для подключения, диоды и многое другое.</p>
<p class="bold line">Транзисторы</p>
<p>Это основные полупроводниковые элементы, которые помогают в создании логических цепочек. У транзистора есть 3 вывода, которые и обеспечивают работу:</p>
<ul>
<li>база, куда подается сигнал;</li>
<li>коллектор, который усиливает сигнал;</li>
<li>эмиттер — проводник схемы.</li>
</ul>',
			'thumbnail'  => 'project1.jpg',
			'top_image'  => 'phoject-detail-top-img.jpg',
			'main_image' => 'project-img1.png',
			'gallery'    => array( 'project-img2.png' ),
		),
		array(
			'title'      => 'Разработка плат для ПК',
			'short_desc' => '<p>Мы предлагаем оперативное создание прототипов для тестирования и проверки ваших концепций, чтобы вы могли оценить результат до старта серийного производства.</p>',
			'content'    => '<h3>Разработка плат для ПК</h3>
<p>Мы предлагаем оперативное создание прототипов для тестирования и проверки ваших концепций, чтобы вы могли оценить результат до старта серийного производства.</p>',
			'thumbnail'  => 'project2.jpg',
			'top_image'  => '',
			'main_image' => '',
			'gallery'    => array(),
		),
		array(
			'title'      => 'Восстановление плат и микросхем',
			'short_desc' => '<p>Мы предлагаем всестороннюю техническую поддержку и консультации на каждом этапе разработки, чтобы обеспечить успешное выполнение вашего проекта.</p>',
			'content'    => '<h3>Восстановление плат и микросхем</h3>
<p>Мы предлагаем всестороннюю техническую поддержку и консультации на каждом этапе разработки, чтобы обеспечить успешное выполнение вашего проекта.</p>',
			'thumbnail'  => 'project3.jpg',
			'top_image'  => '',
			'main_image' => '',
			'gallery'    => array(),
		),
	);

	foreach ( $projects as $project ) {
		$post_id = wp_insert_post( array(
			'post_type'    => 'titan_project',
			'post_title'   => $project['title'],
			'post_content' => $project['content'],
			'post_status'  => 'draft',
		) );

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}

		// Set thumbnail
		if ( $project['thumbnail'] ) {
			$thumb_path = get_template_directory() . '/assets/img/' . $project['thumbnail'];
			$thumb_id   = titan_project_sideload_image( $thumb_path, $post_id );
			if ( $thumb_id ) {
				set_post_thumbnail( $post_id, $thumb_id );
			}
		}

		// ACF fields
		update_field( 'project_short_desc', $project['short_desc'], $post_id );

		if ( $project['top_image'] ) {
			$top_path = get_template_directory() . '/assets/img/' . $project['top_image'];
			$top_id   = titan_project_sideload_image( $top_path, $post_id );
			if ( $top_id ) {
				update_field( 'project_top_image', $top_id, $post_id );
			}
		}

		if ( $project['main_image'] ) {
			$main_path = get_template_directory() . '/assets/img/' . $project['main_image'];
			$main_id   = titan_project_sideload_image( $main_path, $post_id );
			if ( $main_id ) {
				update_field( 'project_main_image', $main_id, $post_id );
			}
		}

		if ( ! empty( $project['gallery'] ) ) {
			$gallery_ids = array();
			foreach ( $project['gallery'] as $img_file ) {
				$img_path = get_template_directory() . '/assets/img/' . $img_file;
				$img_id   = titan_project_sideload_image( $img_path, $post_id );
				if ( $img_id ) {
					$gallery_ids[] = $img_id;
				}
			}
			if ( ! empty( $gallery_ids ) ) {
				update_field( 'project_gallery', $gallery_ids, $post_id );
			}
		}
	}

	update_option( 'titan_projects_demo_v1', true );
}

/**
 * Helper: import image from theme assets into media library.
 */
function titan_project_sideload_image( $file_path, $post_id = 0 ) {
	if ( ! file_exists( $file_path ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$filename  = basename( $file_path );
	$upload    = wp_upload_dir();
	$dest_path = $upload['path'] . '/' . $filename;

	// Avoid duplicates
	if ( file_exists( $dest_path ) ) {
		$existing = get_posts( array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_attached_file',
			'meta_value'     => $upload['subdir'] . '/' . $filename,
			'fields'         => 'ids',
		) );
		if ( ! empty( $existing ) ) {
			return $existing[0];
		}
	}

	copy( $file_path, $dest_path );

	$filetype = wp_check_filetype( $filename );

	$attachment_id = wp_insert_attachment( array(
		'guid'           => $upload['url'] . '/' . $filename,
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	), $dest_path, $post_id );

	if ( ! is_wp_error( $attachment_id ) ) {
		$metadata = wp_generate_attachment_metadata( $attachment_id, $dest_path );
		wp_update_attachment_metadata( $attachment_id, $metadata );
		return $attachment_id;
	}

	return 0;
}
