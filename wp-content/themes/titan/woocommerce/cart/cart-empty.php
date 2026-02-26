<?php
/**
 * Empty cart page.
 */
defined( 'ABSPATH' ) || exit;
?>

<section class="cart-block cart-block--empty">
	<div class="container">
		<div class="title">
			<h1>Корзина пуста</h1>
		</div>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn">В каталог</a>
	</div>
</section>
