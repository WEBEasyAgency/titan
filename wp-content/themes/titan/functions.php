<?php
/**
 * Titan Theme — functions and definitions
 */

// =========================================
// 1. Theme Setup
// =========================================
function titan_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
	add_theme_support( 'woocommerce' );

	register_nav_menus( array(
		'primary' => 'Основное меню (шапка)',
		'footer'  => 'Меню в подвале',
		'mobile'  => 'Мобильное меню (бургер)',
	) );
}
add_action( 'after_setup_theme', 'titan_setup' );

// =========================================
// 2. Enqueue Scripts and Styles
// =========================================
function titan_scripts() {
	$ver = '1.0.0';

	// Styles
	wp_enqueue_style( 'titan-libs', get_template_directory_uri() . '/assets/css/libs.min.css', array(), $ver );
	wp_enqueue_style( 'titan-app', get_template_directory_uri() . '/assets/css/app.min.css', array( 'titan-libs' ), $ver );
	wp_enqueue_style( 'titan-cf7', get_template_directory_uri() . '/assets/css/cf7-custom.css', array( 'titan-app' ), $ver );

	// Replace WP jQuery with bundled jQuery (includes Swiper, Inputmask, Fancybox)
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', get_template_directory_uri() . '/assets/js/libs.min.js', array(), '3.7.1', true );
	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'titan-app', get_template_directory_uri() . '/assets/js/app.min.js', array( 'jquery' ), $ver, true );
}
add_action( 'wp_enqueue_scripts', 'titan_scripts' );

// =========================================
// 3. Custom Nav Walker (clean <li><a> output)
// =========================================
class Titan_Nav_Walker extends Walker_Nav_Menu {
	function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$output .= '<li>';
		$output .= '<a href="' . esc_url( $item->url ) . '">';
		$output .= esc_html( $item->title );
		$output .= '</a>';
	}

	function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}

// =========================================
// 4. WooCommerce Helpers
// =========================================
function titan_cart_url() {
	if ( function_exists( 'wc_get_cart_url' ) ) {
		return wc_get_cart_url();
	}
	return '#';
}

function titan_account_url() {
	if ( function_exists( 'wc_get_page_id' ) ) {
		$page_id = wc_get_page_id( 'myaccount' );
		if ( $page_id > 0 ) {
			return get_permalink( $page_id );
		}
	}
	return '#';
}

function titan_cart_count() {
	if ( function_exists( 'WC' ) && WC()->cart ) {
		return WC()->cart->get_cart_contents_count();
	}
	return 0;
}

// AJAX cart fragment
function titan_cart_count_fragment( $fragments ) {
	ob_start();
	$count = titan_cart_count();
	?>
	<span class="cart-count"><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['span.cart-count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'titan_cart_count_fragment' );

// =========================================
// 5. Auto-create menus on theme activation
// =========================================
function titan_create_menus() {
	// Выполняем только один раз
	if ( get_option( 'titan_setup_v4' ) ) {
		return;
	}

	$menu_items = array(
		'Производство'    => '#',
		'Разработка'      => '#',
		'Интернет-магазин' => '#',
		'Наши проекты'    => '#',
		'Контакты'        => '#',
	);

	$locations = array(
		'primary' => 'Основное меню',
		'footer'  => 'Меню подвала',
		'mobile'  => 'Мобильное меню',
	);

	foreach ( $locations as $location => $menu_name ) {
		$existing = wp_get_nav_menu_object( $menu_name );
		if ( $existing ) {
			$menu_id = $existing->term_id;
		} else {
			$menu_id = wp_create_nav_menu( $menu_name );
			if ( is_wp_error( $menu_id ) ) {
				continue;
			}

			$position = 0;
			foreach ( $menu_items as $title => $url ) {
				$position++;
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'   => $title,
					'menu-item-url'     => $url,
					'menu-item-status'  => 'publish',
					'menu-item-type'    => 'custom',
					'menu-item-position' => $position,
				) );
			}
		}

		$theme_locations = get_theme_mod( 'nav_menu_locations', array() );
		$theme_locations[ $location ] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $theme_locations );
	}

	// Создаём страницы с назначенными шаблонами
	$pages = array(
		'Производство'       => 'page-production.php',
		'Контакты'          => 'page-contacts.php',
	);

	foreach ( $pages as $title => $template ) {
		$exists = get_page_by_title( $title, OBJECT, 'page' );
		if ( ! $exists ) {
			$page_id = wp_insert_post( array(
				'post_title'  => $title,
				'post_status' => 'publish',
				'post_type'   => 'page',
			) );
			if ( $page_id && ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', $template );
			}
		}
	}

	// Обновляем ссылки в меню
	$page_links = array(
		'Производство'    => '',
		'Контакты'        => '',
		'Интернет-магазин' => '',
	);
	foreach ( $page_links as $title => &$url ) {
		$page = get_page_by_title( $title, OBJECT, 'page' );
		if ( $page ) {
			$url = get_permalink( $page->ID );
		}
	}
	unset( $url );

	// Ссылка на магазин WooCommerce
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$page_links['Интернет-магазин'] = wc_get_page_permalink( 'shop' );
	}

	// Обновляем пункты во всех меню
	foreach ( $locations as $location => $menu_name ) {
		$menu_obj = wp_get_nav_menu_object( $menu_name );
		if ( ! $menu_obj ) continue;
		$items = wp_get_nav_menu_items( $menu_obj->term_id );
		if ( ! $items ) continue;
		foreach ( $items as $item ) {
			if ( isset( $page_links[ $item->title ] ) && $page_links[ $item->title ] ) {
				update_post_meta( $item->ID, '_menu_item_url', $page_links[ $item->title ] );
			}
		}
	}

	update_option( 'titan_setup_v4', true );
}
add_action( 'init', 'titan_create_menus' );

// =========================================
// 6. Contact Form 7: Disable default CSS and autop
// =========================================
add_filter( 'wpcf7_load_css', '__return_false' );
add_filter( 'wpcf7_autop_or_not', '__return_false' );

// =========================================
// 7. Cleanup WP Head
// =========================================
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// =========================================
// 8. WooCommerce: Disable default styles
// =========================================
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// =========================================
// 9. WooCommerce: Disable gallery features
// =========================================
function titan_wc_theme_support() {
	remove_theme_support( 'wc-product-gallery-zoom' );
	remove_theme_support( 'wc-product-gallery-lightbox' );
	remove_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'titan_wc_theme_support', 100 );

// =========================================
// 10. WooCommerce: Remove default hooks
// =========================================
function titan_remove_wc_hooks() {
	// Archive wrapping
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	// Archive elements
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Single product elements
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

	// Sidebar
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}
add_action( 'init', 'titan_remove_wc_hooks' );

// =========================================
// 11. WooCommerce: Localize script data
// =========================================
function titan_wc_localize() {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}
	wp_localize_script( 'titan-app', 'titan_wc', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'titan_wc_nonce' ),
		'cart_url' => wc_get_cart_url(),
	) );
}
add_action( 'wp_enqueue_scripts', 'titan_wc_localize', 20 );

// =========================================
// 12. AJAX Product Search
// =========================================
function titan_ajax_product_search() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );
	$term = sanitize_text_field( $_POST['term'] ?? '' );
	if ( strlen( $term ) < 3 ) {
		wp_send_json_success( array() );
	}

	$products = wc_get_products( array(
		'status'  => 'publish',
		'limit'   => 5,
		's'       => $term,
		'orderby' => 'title',
		'order'   => 'ASC',
	) );

	$results = array();
	foreach ( $products as $product ) {
		$img_id  = $product->get_image_id();
		$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : get_template_directory_uri() . '/assets/img/search-img.png';
		$results[] = array(
			'id'    => $product->get_id(),
			'name'  => $product->get_name(),
			'url'   => $product->get_permalink(),
			'price' => strip_tags( wc_price( $product->get_price() ) ),
			'img'   => $img_url,
		);
	}
	wp_send_json_success( $results );
}
add_action( 'wp_ajax_titan_product_search', 'titan_ajax_product_search' );
add_action( 'wp_ajax_nopriv_titan_product_search', 'titan_ajax_product_search' );

// =========================================
// 13. AJAX Cart Update (quantity & remove)
// =========================================
function titan_ajax_update_cart() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );
	$cart_item_key = sanitize_text_field( $_POST['cart_item_key'] ?? '' );
	$quantity      = intval( $_POST['quantity'] ?? 0 );

	if ( ! $cart_item_key ) {
		wp_send_json_error();
	}

	if ( $quantity <= 0 ) {
		WC()->cart->remove_cart_item( $cart_item_key );
	} else {
		WC()->cart->set_quantity( $cart_item_key, $quantity );
	}

	WC()->cart->calculate_totals();

	wp_send_json_success( array(
		'cart_total' => WC()->cart->get_cart_total(),
		'cart_count' => WC()->cart->get_cart_contents_count(),
	) );
}
add_action( 'wp_ajax_titan_update_cart', 'titan_ajax_update_cart' );
add_action( 'wp_ajax_nopriv_titan_update_cart', 'titan_ajax_update_cart' );

// =========================================
// 14. AJAX Add to Cart
// =========================================
function titan_ajax_add_to_cart() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );
	$product_id = intval( $_POST['product_id'] ?? 0 );
	$quantity   = intval( $_POST['quantity'] ?? 1 );

	if ( ! $product_id ) {
		wp_send_json_error( 'Invalid product' );
	}

	$added = WC()->cart->add_to_cart( $product_id, $quantity );
	if ( $added ) {
		wp_send_json_success( array(
			'cart_count' => WC()->cart->get_cart_contents_count(),
		) );
	} else {
		wp_send_json_error( 'Could not add to cart' );
	}
}
add_action( 'wp_ajax_titan_add_to_cart', 'titan_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_titan_add_to_cart', 'titan_ajax_add_to_cart' );

// =========================================
// 15. Create Demo WooCommerce Products
// =========================================
function titan_create_demo_products() {
	if ( get_option( 'titan_wc_demo_v2' ) ) {
		return;
	}
	if ( ! class_exists( 'WC_Product_Simple' ) ) {
		return;
	}

	// Create categories
	$cat_connectors_id = titan_ensure_product_cat( 'Разъемы' );
	$cat_chips_id      = titan_ensure_product_cat( 'Микросхемы' );

	if ( ! $cat_connectors_id || ! $cat_chips_id ) {
		return;
	}

	// Upload placeholder image
	$placeholder_img_id = titan_upload_placeholder_image();

	$products = array(
		array(
			'name'     => 'USB3.1 TYPE-C 24PF-014, Разъём USB, 24 контакта',
			'price'    => 48,
			'stock'    => 150,
			'cat_slug' => 'razemy',
			'attrs'    => array(
				'Количество контактов'     => '24',
				'Высота, мм'              => '3.16 (корпус)',
				'Глубина, мм'             => '7.9',
				'Диапазон рабочих температур' => '-55…+85 °C',
				'Диэлектрическая прочность, В' => '100',
				'Количество циклов коммутации' => '10000 раз',
				'Контактное сопротивление, мОм' => '40',
				'Материал'                => 'корпус-нержавеющая сталь; изолятор-LCP UL94V-0; контакты-Cu-сплав с покрытием',
				'Номинальное напряжение, В' => '5',
				'Номинальный ток, А'       => '5 (VBUS) max; 1.25 (GND); 0.25 (остальные)',
				'Сопротивление изоляции, МОм' => '100',
				'Тип разъёма'             => 'гнездо USB 3.1 тип С',
				'Транспортная упаковка'    => '35*35*44 / 2000',
				'Ширина, мм'              => '8.94',
				'Вес, г'                  => '0.9',
			),
		),
		array(
			'name'     => '543630289, ВЧ-разъем',
			'price'    => 230,
			'stock'    => 0,
			'cat_slug' => 'razemy',
			'attrs'    => array(),
		),
		array(
			'name'     => 'TMM-105-01-G-D-SM',
			'price'    => 180,
			'stock'    => 0,
			'cat_slug' => 'razemy',
			'attrs'    => array(),
		),
		array(
			'name'     => 'L7805ABD2T-TR, D2PAK',
			'price'    => 120,
			'stock'    => 84,
			'cat_slug' => 'razemy',
			'attrs'    => array(),
		),
		array(
			'name'     => 'TPS65135RTER',
			'price'    => 180,
			'stock'    => 230,
			'cat_slug' => 'mikrosxemy',
			'attrs'    => array(),
		),
		array(
			'name'     => 'CC2530F256RHAR',
			'price'    => 230,
			'stock'    => 56,
			'cat_slug' => 'mikrosxemy',
			'attrs'    => array(),
		),
	);

	// Map slugs to IDs
	$cat_map = array(
		'razemy'     => $cat_connectors_id,
		'mikrosxemy' => $cat_chips_id,
	);

	foreach ( $products as $data ) {
		$cat_id = isset( $cat_map[ $data['cat_slug'] ] ) ? intval( $cat_map[ $data['cat_slug'] ] ) : 0;

		// Check if product already exists by name
		$existing = wc_get_products( array(
			'limit'  => 1,
			'status' => 'any',
			's'      => $data['name'],
		) );

		if ( ! empty( $existing ) ) {
			// Product exists — just fix the category via wp_set_object_terms
			$product = $existing[0];
			if ( $cat_id ) {
				wp_set_object_terms( $product->get_id(), $cat_id, 'product_cat' );
			}
			continue;
		}

		$product = new WC_Product_Simple();
		$product->set_name( $data['name'] );
		$product->set_regular_price( $data['price'] );
		$product->set_manage_stock( true );
		$product->set_stock_quantity( $data['stock'] );
		$product->set_stock_status( $data['stock'] > 0 ? 'instock' : 'outofstock' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_status( 'publish' );

		if ( $placeholder_img_id ) {
			$product->set_image_id( $placeholder_img_id );
		}

		if ( ! empty( $data['attrs'] ) ) {
			$wc_attrs = array();
			foreach ( $data['attrs'] as $attr_name => $attr_value ) {
				$attr = new WC_Product_Attribute();
				$attr->set_name( $attr_name );
				$attr->set_options( array( $attr_value ) );
				$attr->set_visible( true );
				$attr->set_variation( false );
				$wc_attrs[] = $attr;
			}
			$product->set_attributes( $wc_attrs );
		}

		$product->save();

		// Assign category directly via wp_set_object_terms (more reliable)
		if ( $cat_id && $product->get_id() ) {
			wp_set_object_terms( $product->get_id(), $cat_id, 'product_cat' );
		}
	}

	update_option( 'titan_wc_demo_v2', true );
}

function titan_ensure_product_cat( $name ) {
	$term = term_exists( $name, 'product_cat' );
	if ( ! $term ) {
		$term = wp_insert_term( $name, 'product_cat' );
	}
	if ( is_wp_error( $term ) ) {
		return 0;
	}
	return is_array( $term ) ? intval( $term['term_id'] ) : intval( $term );
}

function titan_upload_placeholder_image() {
	$file = get_template_directory() . '/assets/img/product-img.jpg';
	if ( ! file_exists( $file ) ) {
		return 0;
	}

	$existing = get_posts( array(
		'post_type'   => 'attachment',
		'meta_key'    => '_titan_placeholder',
		'meta_value'  => '1',
		'numberposts' => 1,
	) );
	if ( $existing ) {
		return $existing[0]->ID;
	}

	$upload = wp_upload_bits( 'product-placeholder.jpg', null, file_get_contents( $file ) );
	if ( $upload['error'] ) {
		return 0;
	}

	$attach_id = wp_insert_attachment( array(
		'post_mime_type' => 'image/jpeg',
		'post_title'     => 'Product Placeholder',
		'post_status'    => 'inherit',
	), $upload['file'] );

	require_once ABSPATH . 'wp-admin/includes/image.php';
	$meta = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
	wp_update_attachment_metadata( $attach_id, $meta );
	update_post_meta( $attach_id, '_titan_placeholder', '1' );

	return $attach_id;
}
add_action( 'init', 'titan_create_demo_products', 20 );

// =========================================
// 16. WooCommerce: Force classic cart/checkout templates
// =========================================
function titan_force_classic_cart_checkout() {
	// Cart page
	$cart_page_id = wc_get_page_id( 'cart' );
	if ( $cart_page_id > 0 ) {
		$cart_page = get_post( $cart_page_id );
		if ( $cart_page && has_blocks( $cart_page->post_content ) ) {
			wp_update_post( array(
				'ID'           => $cart_page_id,
				'post_content' => '[woocommerce_cart]',
			) );
		}
	}

	// Checkout page
	$checkout_page_id = wc_get_page_id( 'checkout' );
	if ( $checkout_page_id > 0 ) {
		$checkout_page = get_post( $checkout_page_id );
		if ( $checkout_page && has_blocks( $checkout_page->post_content ) ) {
			wp_update_post( array(
				'ID'           => $checkout_page_id,
				'post_content' => '[woocommerce_checkout]',
			) );
		}
	}
}
add_action( 'init', 'titan_force_classic_cart_checkout', 25 );

// =========================================
// 17. WooCommerce: Set RUB currency defaults
// =========================================
function titan_set_wc_defaults() {
	if ( get_option( 'titan_wc_defaults_v3' ) ) {
		return;
	}
	if ( ! function_exists( 'WC' ) ) {
		return;
	}
	update_option( 'woocommerce_currency', 'RUB' );
	update_option( 'woocommerce_currency_pos', 'right_space' );
	update_option( 'woocommerce_price_thousand_sep', ' ' );
	update_option( 'woocommerce_price_decimal_sep', ',' );
	update_option( 'woocommerce_price_num_decimals', 0 );
	update_option( 'woocommerce_coming_soon', 'no' );
	update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
	update_option( 'woocommerce_registration_generate_password', 'no' );
	update_option( 'titan_wc_defaults_v3', true );
}
add_action( 'init', 'titan_set_wc_defaults', 5 );

// Save custom fields on WooCommerce registration
function titan_save_wc_registration_fields( $customer_id ) {
	if ( isset( $_POST['user_type'] ) ) {
		update_user_meta( $customer_id, 'user_type', sanitize_text_field( $_POST['user_type'] ) );
	}
	if ( isset( $_POST['inn'] ) ) {
		update_user_meta( $customer_id, 'inn', sanitize_text_field( $_POST['inn'] ) );
	}
	if ( isset( $_POST['surname'] ) ) {
		update_user_meta( $customer_id, 'surname', sanitize_text_field( $_POST['surname'] ) );
	}
}
add_action( 'woocommerce_created_customer', 'titan_save_wc_registration_fields' );

// =========================================
// 19. Catalog Table Renderer
// =========================================
function titan_render_catalog_table( $category_id = null ) {
	$query_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	);
	if ( $category_id ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => intval( $category_id ),
			),
		);
	}
	$query    = new WP_Query( $query_args );
	$products = array_filter( array_map( 'wc_get_product', wp_list_pluck( $query->posts, 'ID' ) ) );
	wp_reset_postdata();

	// Sort products: in-stock first, then out-of-stock
	$in_stock     = array();
	$out_of_stock = array();
	foreach ( $products as $product ) {
		if ( $product->is_in_stock() ) {
			$in_stock[] = $product;
		} else {
			$out_of_stock[] = $product;
		}
	}
	$products = array_merge( $in_stock, $out_of_stock );

	ob_start();
	?>
	<div class="catalog-table">
		<div class="table-head grid">
			<div class="item name-block">Наименование</div>
			<div class="item">Цена за 1 ед.</div>
			<div class="item">Наличие</div>
			<div class="item"></div>
		</div>
		<div class="table-body">
			<?php foreach ( $products as $product ) :
				$in_stock  = $product->is_in_stock();
				$stock_qty = $product->get_stock_quantity();
			?>
			<div class="t-row grid">
				<div class="item name-block">
					<div class="name">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo esc_html( $product->get_name() ); ?>
						</a>
					</div>
				</div>
				<div class="item">
					<div class="price"><?php echo esc_html( $product->get_price() ); ?> ₽</div>
				</div>
				<div class="item">
					<?php if ( $in_stock ) : ?>
						<div class="in-stock">В наличии: <span><?php echo esc_html( $stock_qty ); ?></span></div>
					<?php else : ?>
						<div class="not-in-stock">Нет в наличии</div>
					<?php endif; ?>
				</div>
				<div class="item btn-block">
					<?php if ( $in_stock ) : ?>
						<a href="#" class="btn titan-add-to-cart" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">Купить</a>
					<?php else : ?>
						<a href="#request-product" class="btn btn-gray popup" data-product-name="<?php echo esc_attr( $product->get_name() ); ?>">Запросить</a>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
