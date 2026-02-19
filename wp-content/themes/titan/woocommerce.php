<?php
/**
 * WooCommerce wrapper template.
 * Shop/category and single product pages render through this file.
 * Cart/checkout are regular WP pages — they use page.php / index.php.
 */
get_header();

$main_class = 'inner-page';
if ( is_shop() || is_product_category() || is_product_tag() ) {
	$main_class .= ' catalog-page';
} elseif ( is_product() ) {
	$main_class .= ' product-page';
} else {
	$main_class .= ' wc-page';
}
?>

<main class="<?php echo esc_attr( $main_class ); ?>">
	<?php
	if ( is_shop() || is_product_category() || is_product_tag() ) {
		wc_get_template( 'archive-product.php' );
	} elseif ( is_product() ) {
		wc_get_template( 'single-product.php' );
	} else {
		woocommerce_content();
	}
	?>
</main>

<?php
get_template_part( 'template-parts/wc-scripts' );

if ( is_shop() || is_product_category() || is_product() ) {
	get_template_part( 'template-parts/cf7-scripts' );
}

get_footer();
