<?php
/**
 * Checkout Form — custom Titan template
 *
 * Replaces default WooCommerce form-checkout.php.
 * Preserves standard WC checkout flow (wc-checkout JS, AJAX update_order_review)
 * while maintaining custom UI: buyer tabs, order composition table, legal entities.
 *
 * @see woocommerce/templates/checkout/form-checkout.php (original)
 */
defined( 'ABSPATH' ) || exit;

// Fire the hook but suppress default WC output (login reminder, coupon form)
// that would break our layout. We only need this for plugin compatibility.
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
do_action( 'woocommerce_before_checkout_form', $checkout );

$cart           = WC()->cart;
$user           = wp_get_current_user();
$user_id        = $user->ID;
$last_name      = $user->last_name;
$first_name     = $user->first_name;
$surname        = get_user_meta( $user_id, 'surname', true );
$email          = $user->user_email;
$phone          = get_user_meta( $user_id, 'billing_phone', true );
$legal_entities = titan_get_legal_entities( $user_id );
$cart_items     = $cart->get_cart();
$cart_subtotal  = $cart->get_cart_subtotal();
?>

<?php if ( empty( $cart_items ) ) : ?>
	<div class="checkout-section">
		<h2 class="checkout-section__title">Корзина пуста</h2>
		<p>Добавьте товары в корзину, чтобы оформить заказ.</p>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn">Перейти в магазин</a>
	</div>
<?php else : ?>

<!-- Order Composition -->
<div class="checkout-section">
	<h2 class="checkout-section__title">Состав заказа:</h2>
	<div class="checkout-table">
		<div class="checkout-table__head grid">
			<div class="checkout-table__cell">Фото</div>
			<div class="checkout-table__cell">Наименование</div>
			<div class="checkout-table__cell">Цена за 1 ед.</div>
			<div class="checkout-table__cell">Кол-во</div>
			<div class="checkout-table__cell">Сумма</div>
		</div>
		<div class="checkout-table__body">
			<?php foreach ( $cart_items as $cart_item_key => $cart_item ) :
				$product   = $cart_item['data'];
				$qty       = $cart_item['quantity'];
				$price     = $product->get_price();
				$subtotal  = $cart_item['line_total'];
				$img_id    = $product->get_image_id();
				$img_url   = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : wc_placeholder_img_src( 'thumbnail' );
			?>
			<div class="checkout-table__row grid" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
				<div class="checkout-table__cell photo">
					<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
				</div>
				<div class="checkout-table__cell name"><?php echo esc_html( $product->get_name() ); ?></div>
				<div class="checkout-table__cell price"><?php echo wp_kses_post( wc_price( $price ) ); ?></div>
				<div class="checkout-table__cell quantity">
					<div class="quantity-block">
						<div class="sign minus">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M19 12.998H5V10.998H19V12.998Z" fill="black"/>
							</svg>
						</div>
						<input type="text" class="number" value="<?php echo esc_attr( $qty ); ?>">
						<div class="sign plus">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M19 12.998H13V18.998H11V12.998H5V10.998H11V4.99805H13V10.998H19V12.998Z" fill="black"/>
							</svg>
						</div>
					</div>
				</div>
				<div class="checkout-table__cell total"><?php echo wp_kses_post( wc_price( $subtotal ) ); ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="checkout-subtotal">
		<span>Сумма без доставки</span>
		<span class="checkout-subtotal__val"><?php echo wp_kses_post( $cart_subtotal ); ?></span>
	</div>
</div>

<!-- WC Checkout Form -->
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" data-checkout-url="<?php echo esc_url( WC_AJAX::get_endpoint( 'checkout' ) ); ?>">

	<div class="checkout-section">
		<h2 class="checkout-section__title">Покупатель:</h2>

		<div class="checkout-buyer-content">
			<!-- Buyer Type Tabs -->
			<div class="checkout-buyer-tabs">
				<button type="button" class="checkout-buyer-tab active" data-buyer="physical">Физическое лицо</button>
				<button type="button" class="checkout-buyer-tab" data-buyer="legal">Юридическое лицо</button>
			</div>
			<input type="hidden" id="titan_buyer_type" name="titan_buyer_type" value="physical">

			<!-- Physical Person Panel -->
			<div class="checkout-buyer-panel" data-buyer-panel="physical">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<!-- Legal Person Panel -->
			<div class="checkout-buyer-panel" data-buyer-panel="legal" style="display: none;">
				<div class="checkout-fields">
					<input type="text" name="billing_last_name_legal" value="<?php echo esc_attr( $last_name ); ?>" placeholder="Фамилия" class="input-text">
					<input type="text" name="billing_first_name_legal" value="<?php echo esc_attr( $first_name ); ?>" placeholder="Имя" class="input-text">
					<input type="text" name="billing_patronymic_legal" value="<?php echo esc_attr( $surname ); ?>" placeholder="Отчество" class="input-text">
					<input type="email" name="billing_email_legal" value="<?php echo esc_attr( $email ); ?>" placeholder="Email" class="input-text">
					<input type="tel" name="billing_phone_legal" value="<?php echo esc_attr( $phone ); ?>" placeholder="Телефон" class="input-text">
				</div>

				<div class="checkout-fields">
					<div class="checkout-select">
						<select name="titan_legal_entity">
							<option value="" disabled selected>Выберите покупателя из списка добавленных юридических лиц</option>
							<?php foreach ( $legal_entities as $entity ) : ?>
								<option value="<?php echo esc_attr( $entity['id'] ); ?>"
									data-inn="<?php echo esc_attr( $entity['inn'] ); ?>"
									data-kpp="<?php echo esc_attr( $entity['kpp'] ); ?>">
									<?php echo esc_html( $entity['org_name'] ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<div class="checkout-select__arrow">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M8 10L4 6H12L8 10Z" fill="black"/>
							</svg>
						</div>
					</div>
					<input type="text" name="titan_inn" placeholder="ИНН Организации или ИП" class="input-text">
					<input type="text" name="titan_kpp" placeholder="КПП Организации или ИП" class="input-text">
					<textarea name="order_comments_legal" placeholder="Комментарий к заказу" rows="4" class="input-text"></textarea>
				</div>

				<!-- File Attachments -->
				<div class="checkout-attachments">
					<div class="checkout-attachment" style="display: none;">
						<span class="checkout-attachment__name"></span>
						<button type="button" class="checkout-attachment__delete">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M20 5C20 5.55228 19.5523 6 19 6H18.997L18.064 19.142C18.0281 19.6466 17.8023 20.1188 17.4321 20.4636C17.0619 20.8083 16.5749 20.9999 16.069 21H7.93C7.42414 20.9999 6.93707 20.8083 6.56688 20.4636C6.19669 20.1188 5.97093 19.6466 5.935 19.142L5.003 6H5C4.44772 6 4 5.55228 4 5C4 4.44772 4.44772 4 5 4H9C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4H19C19.5523 4 20 4.44772 20 5ZM7.003 6L7.931 19H16.069L16.997 6H7.003Z" fill="black"/>
							</svg>
						</button>
					</div>
					<label class="btn btn-outline checkout-attach-btn">
						<input type="file" name="titan_requisites_file" style="display: none;">
						Прикрепить реквизиты
					</label>
					<input type="hidden" name="titan_requisites_id" value="">
				</div>

				<label class="checkbox">
					<input type="checkbox" name="titan_authorized" value="1">
					<span class="check"></span>
					<span class="label">Я подтверждаю, что уполномочен представлять интересы данного юридического лица или ИП</span>
				</label>
				<label class="checkbox">
					<input type="checkbox" name="titan_personal_data_legal" value="1">
					<span class="check"></span>
					<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ «О персональных данных»</span>
				</label>
			</div>

			<!-- Order Review: shipping + totals + payment + submit -->
			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<?php endif; ?>
