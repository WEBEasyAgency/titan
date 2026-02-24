<?php
/**
 * Template for regular WordPress pages.
 * Also handles WooCommerce cart/checkout/account pages.
 */
get_header();

$main_class = 'inner-page';

// Detect WooCommerce pages by function
if ( function_exists( 'is_cart' ) && is_cart() ) {
	$main_class .= ' cart-page';
} elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
	$main_class .= ' checkout-page';
} elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
	$main_class .= ' account-page';
}
?>

<main class="<?php echo esc_attr( $main_class ); ?>">
	<?php
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
	?>
</main>

<?php
// Add WC scripts if this is a cart page
if ( function_exists( 'is_cart' ) && is_cart() ) {
	get_template_part( 'template-parts/wc-scripts' );
}

get_footer();
