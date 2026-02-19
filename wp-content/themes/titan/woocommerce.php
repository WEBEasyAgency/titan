<?php
/**
 * WooCommerce wrapper template.
 * All WC pages (shop, product, cart, checkout) render through this file.
 */
get_header();

$main_class = 'inner-page';
if ( is_shop() || is_product_category() || is_product_tag() ) {
	$main_class .= ' catalog-page';
} elseif ( is_product() ) {
	$main_class .= ' product-page';
} elseif ( is_cart() ) {
	$main_class .= ' cart-page';
} else {
	$main_class .= ' wc-page';
}
?>

<main class="<?php echo esc_attr( $main_class ); ?>">
	<?php woocommerce_content(); ?>
</main>

<?php
get_template_part( 'template-parts/wc-scripts' );

if ( is_shop() || is_product_category() || is_product() ) {
	get_template_part( 'template-parts/cf7-scripts' );
}

get_footer();
