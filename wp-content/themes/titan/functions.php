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
	if ( get_option( 'titan_menus_created' ) ) {
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

	update_option( 'titan_menus_created', true );
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
