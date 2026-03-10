<?php
/**
 * Custom Post Type: Заявки (titan_request)
 *
 * Saves CF7 form submissions as CPT entries,
 * sends email notification to admin, displays data in admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =========================================
// 1. Register CPT
// =========================================
add_action( 'init', 'titan_register_cpt_request' );

function titan_register_cpt_request() {
	register_post_type( 'titan_request', array(
		'labels' => array(
			'name'               => 'Заявки',
			'singular_name'      => 'Заявка',
			'add_new'            => 'Добавить заявку',
			'add_new_item'       => 'Новая заявка',
			'edit_item'          => 'Просмотр заявки',
			'view_item'          => 'Смотреть заявку',
			'all_items'          => 'Все заявки',
			'search_items'       => 'Поиск заявок',
			'not_found'          => 'Заявок не найдено',
			'not_found_in_trash' => 'В корзине пусто',
			'menu_name'          => 'Заявки',
		),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 25,
		'menu_icon'           => 'dashicons-email-alt',
		'supports'            => array( 'title' ),
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		'has_archive'         => false,
		'rewrite'             => false,
		'query_var'           => false,
		'exclude_from_search' => true,
	) );
}

// =========================================
// 2. CF7 Hook: save submission
// =========================================
add_action( 'wpcf7_mail_sent', 'titan_save_cf7_request' );

function titan_save_cf7_request( $contact_form ) {
	$submission = WPCF7_Submission::get_instance();
	if ( ! $submission ) {
		return;
	}

	$data = $submission->get_posted_data();

	$name    = isset( $data['your-name'] ) ? sanitize_text_field( $data['your-name'] ) : '';
	$phone   = isset( $data['your-tel'] ) ? sanitize_text_field( $data['your-tel'] ) : '';
	$email   = isset( $data['your-email'] ) ? sanitize_email( $data['your-email'] ) : '';
	$message = isset( $data['your-message'] ) ? sanitize_textarea_field( $data['your-message'] ) : '';

	$referer = '';
	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		$referer = esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) );
	}

	$title = $name ?: 'Без имени';
	if ( $phone ) {
		$title .= ' — ' . $phone;
	}

	$post_id = wp_insert_post( array(
		'post_type'   => 'titan_request',
		'post_title'  => $title,
		'post_status' => 'publish',
	) );

	if ( ! $post_id || is_wp_error( $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, '_request_name', $name );
	update_post_meta( $post_id, '_request_phone', $phone );
	update_post_meta( $post_id, '_request_email', $email );
	update_post_meta( $post_id, '_request_message', $message );
	update_post_meta( $post_id, '_request_page', $referer );

	// Upload files to media library and attach to request
	$files = $submission->uploaded_files();
	if ( ! empty( $files ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$attachment_ids = array();
		foreach ( $files as $field_name => $paths ) {
			$paths = is_array( $paths ) ? $paths : array( $paths );
			foreach ( $paths as $path ) {
				if ( ! file_exists( $path ) ) {
					continue;
				}
				$file_array = array(
					'name'     => basename( $path ),
					'tmp_name' => $path,
				);
				$attach_id = media_handle_sideload( $file_array, $post_id );
				if ( ! is_wp_error( $attach_id ) ) {
					$attachment_ids[] = $attach_id;
				}
			}
		}
		if ( ! empty( $attachment_ids ) ) {
			update_post_meta( $post_id, '_request_files', $attachment_ids );
		}
	}

	// Send email to admin
	titan_send_request_email( $post_id, $name, $phone, $email, $message, $referer );
}

// =========================================
// 3. Email notification
// =========================================
function titan_send_request_email( $post_id, $name, $phone, $email, $message, $page ) {
	$admin_email = get_option( 'admin_email' );
	if ( ! $admin_email ) {
		return;
	}

	$subject = 'Новая заявка с сайта: ' . $name;

	$body  = "Новая заявка с сайта\n\n";
	$body .= "Имя: {$name}\n";
	$body .= "Телефон: {$phone}\n";
	$body .= "Email: {$email}\n";
	if ( $message ) {
		$body .= "Сообщение: {$message}\n";
	}
	if ( $page ) {
		$body .= "Страница: {$page}\n";
	}
	$body .= "\nПросмотр в админке: " . admin_url( "post.php?post={$post_id}&action=edit" ) . "\n";

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}

	wp_mail( $admin_email, $subject, $body, $headers );
}

// =========================================
// 4. Meta box: display request data
// =========================================
add_action( 'add_meta_boxes', 'titan_request_meta_boxes' );

function titan_request_meta_boxes() {
	add_meta_box(
		'titan_request_data',
		'Данные заявки',
		'titan_request_meta_box_callback',
		'titan_request',
		'normal',
		'high'
	);
}

function titan_request_meta_box_callback( $post ) {
	$fields = array(
		'_request_name'    => 'Имя',
		'_request_phone'   => 'Телефон',
		'_request_email'   => 'Email',
		'_request_message' => 'Сообщение',
		'_request_page'    => 'Страница',
	);

	echo '<table class="form-table">';
	foreach ( $fields as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		if ( ! $value ) {
			continue;
		}
		echo '<tr>';
		echo '<th style="width:150px;">' . esc_html( $label ) . '</th>';
		echo '<td>';
		if ( $key === '_request_email' ) {
			echo '<a href="mailto:' . esc_attr( $value ) . '">' . esc_html( $value ) . '</a>';
		} elseif ( $key === '_request_phone' ) {
			echo '<a href="tel:' . esc_attr( $value ) . '">' . esc_html( $value ) . '</a>';
		} elseif ( $key === '_request_page' ) {
			echo '<a href="' . esc_url( $value ) . '" target="_blank">' . esc_html( $value ) . '</a>';
		} else {
			echo nl2br( esc_html( $value ) );
		}
		echo '</td>';
		echo '</tr>';
	}

	// Files
	$attachment_ids = get_post_meta( $post->ID, '_request_files', true );
	if ( ! empty( $attachment_ids ) && is_array( $attachment_ids ) ) {
		echo '<tr>';
		echo '<th style="width:150px;">Файлы</th>';
		echo '<td>';
		foreach ( $attachment_ids as $att_id ) {
			$url  = wp_get_attachment_url( $att_id );
			$name = get_the_title( $att_id );
			if ( ! $name ) {
				$name = basename( get_attached_file( $att_id ) );
			}
			if ( $url ) {
				echo '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( $name ) . '</a><br>';
			}
		}
		echo '</td>';
		echo '</tr>';
	}

	echo '</table>';
}

// =========================================
// 5. Admin columns
// =========================================
add_filter( 'manage_titan_request_posts_columns', 'titan_request_columns' );

function titan_request_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb']    = $columns['cb'];
	$new_columns['title'] = 'Имя';
	$new_columns['phone'] = 'Телефон';
	$new_columns['email'] = 'Email';
	$new_columns['page']  = 'Страница';
	$new_columns['date']  = $columns['date'];
	return $new_columns;
}

add_action( 'manage_titan_request_posts_custom_column', 'titan_request_column_data', 10, 2 );

function titan_request_column_data( $column, $post_id ) {
	switch ( $column ) {
		case 'phone':
			$phone = get_post_meta( $post_id, '_request_phone', true );
			echo $phone ? esc_html( $phone ) : '&mdash;';
			break;
		case 'email':
			$email = get_post_meta( $post_id, '_request_email', true );
			if ( $email ) {
				echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
			} else {
				echo '&mdash;';
			}
			break;
		case 'page':
			$page = get_post_meta( $post_id, '_request_page', true );
			if ( $page ) {
				$parsed = wp_parse_url( $page );
				$display = isset( $parsed['path'] ) ? $parsed['path'] : $page;
				echo '<a href="' . esc_url( $page ) . '" target="_blank">' . esc_html( $display ) . '</a>';
			} else {
				echo '&mdash;';
			}
			break;
	}
}
