<?php
/**
 * WooCommerce Cart template.
 * Custom layout matching the Titan design.
 */
defined( 'ABSPATH' ) || exit;
?>

<section class="cart-block">
	<div class="container">
		<div class="title">
			<h1>Корзина</h1>
		</div>

		<?php if ( WC()->cart->is_empty() ) : ?>
			<div class="empty-cart" style="padding: 40px 0; text-align: center;">
				<p>Ваша корзина пуста</p>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn" style="display: inline-block; margin-top: 16px;">Перейти в каталог</a>
			</div>
		<?php else : ?>

		<div class="cart-table">
			<div class="table-head grid">
				<div class="item photo">Фото</div>
				<div class="item name">Наименование</div>
				<div class="item">Цена за 1 ед.</div>
				<div class="item">Кол-во</div>
				<div class="item"><a href="#" class="delete-all" id="titan-clear-cart">Удалить все</a></div>
			</div>
			<div class="table-body">
				<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
					$product    = $cart_item['data'];
					$quantity   = $cart_item['quantity'];
					$img_url    = wp_get_attachment_url( $product->get_image_id() );
					if ( ! $img_url ) {
						$img_url = get_template_directory_uri() . '/assets/img/product-img.jpg';
					}
				?>
				<div class="t-row grid" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
					<div class="item photo">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
						</a>
					</div>
					<div class="item name">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
					</div>
					<div class="item">
						<div class="price"><?php echo esc_html( $product->get_price() ); ?> ₽</div>
					</div>
					<div class="item">
						<div class="quantity-block">
							<div class="sign minus">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M19 12.998H5V10.998H19V12.998Z" fill="black"/>
								</svg>
							</div>
							<input type="text" class="number" value="<?php echo esc_attr( $quantity ); ?>">
							<div class="sign plus">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M19 12.998H13V18.998H11V12.998H5V10.998H11V4.99805H13V10.998H19V12.998Z" fill="black"/>
								</svg>
							</div>
						</div>
					</div>
					<div class="item delete">
						<a href="#" class="titan-remove-cart-item">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M20 5C20.2652 5 20.5196 5.10536 20.7071 5.29289C20.8946 5.48043 21 5.73478 21 6C21 6.26522 20.8946 6.51957 20.7071 6.70711C20.5196 6.89464 20.2652 7 20 7H19L18.997 7.071L18.064 20.142C18.0281 20.6466 17.8023 21.1188 17.4321 21.4636C17.0619 21.8083 16.5749 22 16.069 22H7.93C7.42414 22 6.93707 21.8083 6.56688 21.4636C6.1967 21.1188 5.97092 20.6466 5.935 20.142L5.002 7.072L5 7H4C3.73478 7 3.48043 6.89464 3.29289 6.70711C3.10536 6.51957 3 6.26522 3 6C3 5.73478 3.10536 5.48043 3.29289 5.29289C3.48043 5.10536 3.73478 5 4 5H20ZM16.997 7H7.003L7.931 20H16.069L16.997 7ZM14 2C14.2652 2 14.5196 2.10536 14.7071 2.29289C14.8946 2.48043 15 2.73478 15 3C15 3.26522 14.8946 3.51957 14.7071 3.70711C14.5196 3.89464 14.2652 4 14 4H10C9.73478 4 9.48043 3.89464 9.29289 3.70711C9.10536 3.51957 9 3.26522 9 3C9 2.73478 9.10536 2.48043 9.29289 2.29289C9.48043 2.10536 9.73478 2 10 2H14Z" fill="black"/>
							</svg>
						</a>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( ! is_user_logged_in() ) : ?>
		<div class="cart-auth-notice">
			<p>Для оформления заказа <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">авторизуйтесь или зарегистрируйтесь</a></p>
		</div>
		<?php endif; ?>

		<div class="total-container">
			<div class="total-btn">
				<div class="total">
					<div class="caption">Итого</div>
					<div class="val" id="titan-cart-total"><?php echo WC()->cart->get_cart_total(); ?></div>
				</div>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn">Оформить заказ</a>
				<?php else : ?>
					<span class="btn btn-disabled">Оформить заказ</span>
				<?php endif; ?>
			</div>
		</div>

		<?php endif; ?>
	</div>
</section>
