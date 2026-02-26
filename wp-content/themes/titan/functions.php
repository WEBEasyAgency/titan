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
	$hidden = $count < 1 ? ' hidden' : '';
	?>
	<span class="cart-count<?php echo $hidden; ?>"><?php echo esc_html( $count ); ?></span>
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
		'ajax_url'    => admin_url( 'admin-ajax.php' ),
		'nonce'       => wp_create_nonce( 'titan_wc_nonce' ),
		'cart_url'    => wc_get_cart_url(),
		'account_url' => wc_get_page_permalink( 'myaccount' ),
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

// Redirect logged-in users from /checkout/ to account checkout
function titan_redirect_checkout_to_account() {
	if ( ! is_checkout() || is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' ) ) {
		return;
	}

	if ( is_user_logged_in() ) {
		$account_url = wc_get_page_permalink( 'myaccount' );
		wp_safe_redirect( wc_get_endpoint_url( 'titan-checkout', '', $account_url ) );
		exit;
	}
}
add_action( 'template_redirect', 'titan_redirect_checkout_to_account' );

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

// =========================================
// 20. My Account: Custom Endpoints
// =========================================
function titan_account_endpoints() {
	add_rewrite_endpoint( 'titan-checkout', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'titan-orders', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'titan-history', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'titan_account_endpoints' );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'titan-checkout';
	$vars[] = 'titan-orders';
	$vars[] = 'titan-history';
	return $vars;
} );

// Register custom menu items for My Account
function titan_account_menu_items( $items ) {
	$new_items = array(
		'dashboard'      => 'Профиль',
		'titan-checkout' => 'Оформление заказа',
		'titan-orders'   => 'Заказы',
		'titan-history'  => 'История заказов',
		'customer-logout' => 'Выйти',
	);
	return $new_items;
}
add_filter( 'woocommerce_account_menu_items', 'titan_account_menu_items' );

// Endpoint content callbacks
function titan_account_checkout_content() {
	wc_get_template( 'myaccount/titan-checkout.php' );
}
add_action( 'woocommerce_account_titan-checkout_endpoint', 'titan_account_checkout_content' );

function titan_account_orders_content() {
	wc_get_template( 'myaccount/titan-orders.php' );
}
add_action( 'woocommerce_account_titan-orders_endpoint', 'titan_account_orders_content' );

function titan_account_history_content() {
	wc_get_template( 'myaccount/titan-history.php' );
}
add_action( 'woocommerce_account_titan-history_endpoint', 'titan_account_history_content' );

// Endpoint titles
add_filter( 'woocommerce_endpoint_titan-checkout_title', function() { return 'Оформление заказа'; } );
add_filter( 'woocommerce_endpoint_titan-orders_title', function() { return 'Заказы'; } );
add_filter( 'woocommerce_endpoint_titan-history_title', function() { return 'История заказов'; } );

// Flush rewrite rules on theme switch
function titan_flush_rewrite_rules() {
	titan_account_endpoints();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'titan_flush_rewrite_rules' );

// =========================================
// 21. CPT: Legal Entity
// =========================================
function titan_register_legal_entity_cpt() {
	register_post_type( 'legal_entity', array(
		'labels'       => array(
			'name'          => 'Юридические лица',
			'singular_name' => 'Юридическое лицо',
		),
		'public'       => false,
		'show_ui'      => false,
		'show_in_menu' => false,
		'supports'     => array( 'title' ),
	) );
}
add_action( 'init', 'titan_register_legal_entity_cpt' );

/**
 * Get legal entities for a user.
 */
function titan_get_legal_entities( $user_id = null ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}
	$posts = get_posts( array(
		'post_type'      => 'legal_entity',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'meta_key'       => '_legal_user_id',
		'meta_value'     => $user_id,
		'orderby'        => 'date',
		'order'          => 'ASC',
	) );

	$entities = array();
	foreach ( $posts as $post ) {
		$entities[] = array(
			'id'          => $post->ID,
			'org_name'    => get_post_meta( $post->ID, '_legal_org_name', true ),
			'inn'         => get_post_meta( $post->ID, '_legal_inn', true ),
			'kpp'         => get_post_meta( $post->ID, '_legal_kpp', true ),
			'address'     => get_post_meta( $post->ID, '_legal_address', true ),
			'postal_code' => get_post_meta( $post->ID, '_legal_postal_code', true ),
			'region'      => get_post_meta( $post->ID, '_legal_region', true ),
			'district'    => get_post_meta( $post->ID, '_legal_district', true ),
			'city'        => get_post_meta( $post->ID, '_legal_city', true ),
			'office'      => get_post_meta( $post->ID, '_legal_office', true ),
		);
	}
	return $entities;
}

// =========================================
// 22. AJAX: Update Profile
// =========================================
function titan_ajax_update_profile() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Not authorized' );
	}

	$user_id = get_current_user_id();

	$last_name  = sanitize_text_field( $_POST['last_name'] ?? '' );
	$first_name = sanitize_text_field( $_POST['first_name'] ?? '' );
	$surname    = sanitize_text_field( $_POST['surname'] ?? '' );
	$phone      = sanitize_text_field( $_POST['phone'] ?? '' );

	wp_update_user( array(
		'ID'         => $user_id,
		'first_name' => $first_name,
		'last_name'  => $last_name,
	) );

	update_user_meta( $user_id, 'surname', $surname );
	update_user_meta( $user_id, 'billing_phone', $phone );
	update_user_meta( $user_id, 'billing_first_name', $first_name );
	update_user_meta( $user_id, 'billing_last_name', $last_name );

	$user = get_userdata( $user_id );
	$full_name = trim( $last_name . ' ' . $first_name . ' ' . $surname );
	$user_type_raw = get_user_meta( $user_id, 'user_type', true );
	$user_type_label = $user_type_raw === 'business' ? 'Юридическое лицо' : 'Физическое лицо';

	wp_send_json_success( array(
		'full_name'  => $full_name,
		'email'      => $user->user_email,
		'phone'      => $phone,
		'user_type'  => $user_type_label,
		'last_name'  => $last_name,
		'first_name' => $first_name,
		'surname'    => $surname,
	) );
}
add_action( 'wp_ajax_titan_update_profile', 'titan_ajax_update_profile' );

// =========================================
// 23. AJAX: Change Password
// =========================================
function titan_ajax_change_password() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Not authorized' );
	}

	$user = wp_get_current_user();
	$current  = $_POST['current_password'] ?? '';
	$new_pass = $_POST['new_password'] ?? '';
	$confirm  = $_POST['confirm_password'] ?? '';

	if ( ! wp_check_password( $current, $user->user_pass, $user->ID ) ) {
		wp_send_json_error( 'Неверный текущий пароль' );
	}

	if ( strlen( $new_pass ) < 6 ) {
		wp_send_json_error( 'Пароль должен содержать минимум 6 символов' );
	}

	if ( $new_pass !== $confirm ) {
		wp_send_json_error( 'Пароли не совпадают' );
	}

	wp_set_password( $new_pass, $user->ID );
	wp_set_auth_cookie( $user->ID );
	wp_set_current_user( $user->ID );

	wp_send_json_success();
}
add_action( 'wp_ajax_titan_change_password', 'titan_ajax_change_password' );

// =========================================
// 24. AJAX: Legal Entity CRUD
// =========================================
function titan_ajax_legal_entity_save() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Not authorized' );
	}

	$user_id   = get_current_user_id();
	$entity_id = intval( $_POST['entity_id'] ?? 0 );

	$fields = array(
		'org_name'    => sanitize_text_field( $_POST['org_name'] ?? '' ),
		'inn'         => sanitize_text_field( $_POST['inn'] ?? '' ),
		'kpp'         => sanitize_text_field( $_POST['kpp'] ?? '' ),
		'address'     => sanitize_text_field( $_POST['legal_address'] ?? '' ),
		'postal_code' => sanitize_text_field( $_POST['postal_code'] ?? '' ),
		'region'      => sanitize_text_field( $_POST['region'] ?? '' ),
		'district'    => sanitize_text_field( $_POST['district'] ?? '' ),
		'city'        => sanitize_text_field( $_POST['city'] ?? '' ),
		'office'      => sanitize_text_field( $_POST['address'] ?? '' ),
	);

	if ( empty( $fields['org_name'] ) ) {
		wp_send_json_error( 'Наименование организации обязательно' );
	}

	if ( $entity_id ) {
		// Update existing
		$post = get_post( $entity_id );
		if ( ! $post || $post->post_type !== 'legal_entity' ) {
			wp_send_json_error( 'Entity not found' );
		}
		$owner = get_post_meta( $entity_id, '_legal_user_id', true );
		if ( intval( $owner ) !== $user_id ) {
			wp_send_json_error( 'Access denied' );
		}

		wp_update_post( array(
			'ID'         => $entity_id,
			'post_title' => $fields['org_name'],
		) );
	} else {
		// Create new
		$entity_id = wp_insert_post( array(
			'post_type'   => 'legal_entity',
			'post_title'  => $fields['org_name'],
			'post_status' => 'publish',
		) );

		if ( is_wp_error( $entity_id ) ) {
			wp_send_json_error( 'Failed to create entity' );
		}

		update_post_meta( $entity_id, '_legal_user_id', $user_id );
	}

	// Save meta fields
	foreach ( $fields as $key => $value ) {
		update_post_meta( $entity_id, '_legal_' . $key, $value );
	}

	wp_send_json_success( array(
		'id'     => $entity_id,
		'fields' => $fields,
	) );
}
add_action( 'wp_ajax_titan_legal_entity_save', 'titan_ajax_legal_entity_save' );

function titan_ajax_legal_entity_delete() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Not authorized' );
	}

	$user_id   = get_current_user_id();
	$entity_id = intval( $_POST['entity_id'] ?? 0 );

	if ( ! $entity_id ) {
		wp_send_json_error( 'Invalid entity' );
	}

	$post = get_post( $entity_id );
	if ( ! $post || $post->post_type !== 'legal_entity' ) {
		wp_send_json_error( 'Entity not found' );
	}

	$owner = get_post_meta( $entity_id, '_legal_user_id', true );
	if ( intval( $owner ) !== $user_id ) {
		wp_send_json_error( 'Access denied' );
	}

	wp_delete_post( $entity_id, true );
	wp_send_json_success();
}
add_action( 'wp_ajax_titan_legal_entity_delete', 'titan_ajax_legal_entity_delete' );

// =========================================
// 25. AJAX: Place Order
// =========================================
function titan_ajax_place_order() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Not authorized' );
	}

	$cart = WC()->cart;
	if ( $cart->is_empty() ) {
		wp_send_json_error( 'Корзина пуста' );
	}

	$user_id    = get_current_user_id();
	$user       = get_userdata( $user_id );
	$buyer_type = sanitize_text_field( $_POST['buyer_type'] ?? 'physical' );

	$order = wc_create_order( array(
		'customer_id' => $user_id,
		'status'      => 'pending',
	) );

	if ( is_wp_error( $order ) ) {
		wp_send_json_error( 'Не удалось создать заказ' );
	}

	// Add cart items to order
	foreach ( $cart->get_cart() as $cart_item ) {
		$product  = $cart_item['data'];
		$quantity = $cart_item['quantity'];
		$order->add_product( $product, $quantity );
	}

	// Set billing info
	$last_name  = sanitize_text_field( $_POST['last_name'] ?? $user->last_name );
	$first_name = sanitize_text_field( $_POST['first_name'] ?? $user->first_name );
	$email      = sanitize_email( $_POST['email'] ?? $user->user_email );
	$phone      = sanitize_text_field( $_POST['phone'] ?? get_user_meta( $user_id, 'billing_phone', true ) );

	$order->set_billing_first_name( $first_name );
	$order->set_billing_last_name( $last_name );
	$order->set_billing_email( $email );
	$order->set_billing_phone( $phone );

	// Save custom meta
	$order->update_meta_data( '_buyer_type', $buyer_type );
	$order->update_meta_data( '_surname', sanitize_text_field( $_POST['surname'] ?? '' ) );

	if ( $buyer_type === 'physical' ) {
		$delivery_method = sanitize_text_field( $_POST['delivery_method'] ?? 'delivery' );
		$order->update_meta_data( '_delivery_method', $delivery_method );
		$order->update_meta_data( '_recipient_name', sanitize_text_field( $_POST['recipient'] ?? '' ) );
		$order->update_meta_data( '_order_comment', sanitize_textarea_field( $_POST['comment'] ?? '' ) );
	} else {
		$legal_entity_id = intval( $_POST['legal_entity_id'] ?? 0 );
		$order->update_meta_data( '_legal_entity_id', $legal_entity_id );
		$order->update_meta_data( '_legal_inn', sanitize_text_field( $_POST['inn'] ?? '' ) );
		$order->update_meta_data( '_legal_kpp', sanitize_text_field( $_POST['kpp'] ?? '' ) );
		$order->update_meta_data( '_order_comment', sanitize_textarea_field( $_POST['comment'] ?? '' ) );
	}

	$order->calculate_totals();
	$order->save();

	// Handle file upload for legal entity
	if ( $buyer_type === 'legal' && ! empty( $_FILES['requisites'] ) && $_FILES['requisites']['error'] === UPLOAD_ERR_OK ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$attach_id = media_handle_upload( 'requisites', 0 );
		if ( ! is_wp_error( $attach_id ) ) {
			$order->update_meta_data( '_requisites_file_id', $attach_id );
			$order->save();
		}
	}

	// Clear the cart
	$cart->empty_cart();

	$response = array(
		'order_id' => $order->get_id(),
	);

	// Physical person → redirect to payment gateway
	if ( $buyer_type === 'physical' ) {
		// Set default payment gateway
		$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
		if ( ! empty( $available_gateways ) ) {
			$gateway = reset( $available_gateways );
			$order->set_payment_method( $gateway );
			$order->save();
		}
		$response['payment_url'] = $order->get_checkout_payment_url();
	} else {
		// Legal entity → "on-hold" status (invoice issued, no online payment)
		$order->update_status( 'on-hold', 'Выставлен счёт для юридического лица' );
	}

	wp_send_json_success( $response );
}
add_action( 'wp_ajax_titan_place_order', 'titan_ajax_place_order' );

// =========================================
// 26. AJAX: Update Checkout Quantity
// =========================================
function titan_ajax_update_checkout_qty() {
	check_ajax_referer( 'titan_wc_nonce', 'nonce' );

	$cart_item_key = sanitize_text_field( $_POST['cart_item_key'] ?? '' );
	$quantity      = intval( $_POST['quantity'] ?? 1 );

	if ( ! $cart_item_key ) {
		wp_send_json_error();
	}

	if ( $quantity <= 0 ) {
		WC()->cart->remove_cart_item( $cart_item_key );
	} else {
		WC()->cart->set_quantity( $cart_item_key, $quantity );
	}

	WC()->cart->calculate_totals();

	// Build updated cart data
	$items = array();
	foreach ( WC()->cart->get_cart() as $key => $cart_item ) {
		$product = $cart_item['data'];
		$items[] = array(
			'key'       => $key,
			'price'     => $product->get_price(),
			'quantity'  => $cart_item['quantity'],
			'subtotal'  => $cart_item['line_total'],
		);
	}

	wp_send_json_success( array(
		'items'      => $items,
		'subtotal'   => WC()->cart->get_cart_subtotal(),
		'total'      => WC()->cart->get_cart_total(),
		'total_raw'  => WC()->cart->get_total( 'raw' ),
		'cart_count' => WC()->cart->get_cart_contents_count(),
	) );
}
add_action( 'wp_ajax_titan_update_checkout_qty', 'titan_ajax_update_checkout_qty' );

// =========================================
// 27. WooCommerce: Hide default navigation wrapper
// =========================================
function titan_remove_wc_account_navigation() {
	remove_action( 'woocommerce_account_navigation', 'woocommerce_account_navigation' );
}
add_action( 'init', 'titan_remove_wc_account_navigation' );

// Helper: Get WC order status label in Russian
function titan_order_status_label( $status ) {
	$statuses = array(
		'pending'    => 'Ожидает оплаты',
		'processing' => 'В процессе',
		'on-hold'    => 'На удержании',
		'completed'  => 'Получен',
		'cancelled'  => 'Отменён',
		'refunded'   => 'Возвращён',
		'failed'     => 'Неудачный',
	);
	$status = str_replace( 'wc-', '', $status );
	return $statuses[ $status ] ?? $status;
}

