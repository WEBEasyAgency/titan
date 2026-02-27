<?php
/**
 * My Account — Checkout tab (Оформление заказа)
 */
defined( 'ABSPATH' ) || exit;

$cart  = WC()->cart;
$user  = wp_get_current_user();
$user_id = $user->ID;

$last_name  = $user->last_name;
$first_name = $user->first_name;
$surname    = get_user_meta( $user_id, 'surname', true );
$email      = $user->user_email;
$phone      = get_user_meta( $user_id, 'billing_phone', true );

$legal_entities = titan_get_legal_entities( $user_id );
$cart_items     = $cart->get_cart();
$cart_subtotal  = $cart->get_cart_subtotal();
$cart_total     = $cart->get_cart_total();
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

<!-- Buyer Section -->
<div class="checkout-section">
	<h2 class="checkout-section__title">Покупатель:</h2>

	<!-- Buyer Type Tabs -->
	<div class="checkout-buyer-tabs">
		<button class="checkout-buyer-tab active" data-buyer="physical">Физическое лицо</button>
		<button class="checkout-buyer-tab" data-buyer="legal">Юридическое лицо</button>
	</div>

	<!-- Physical Person Form -->
	<div class="checkout-buyer-panel" data-buyer-panel="physical">
		<div class="checkout-fields">
			<input type="text" name="lastname" value="<?php echo esc_attr( $last_name ); ?>" placeholder="Фамилия">
			<input type="text" name="firstname" value="<?php echo esc_attr( $first_name ); ?>" placeholder="Имя">
			<input type="text" name="patronymic" value="<?php echo esc_attr( $surname ); ?>" placeholder="Отчество">
			<input type="email" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="Email">
			<input type="tel" name="phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="Телефон">
		</div>

		<!-- Delivery Tabs -->
		<div class="checkout-delivery-tabs">
			<button class="checkout-delivery-tab active" data-delivery="delivery">Доставка</button>
			<button class="checkout-delivery-tab" data-delivery="pickup">Самовывоз</button>
		</div>

		<!-- CDEK Module -->
		<div class="checkout-delivery-panel" data-delivery-panel="delivery">
			<div class="checkout-cdek-module">
				<div class="cdek-city-row">
					<input type="text" name="cdek_city" class="cdek-city-input" placeholder="Введите город доставки" autocomplete="off">
					<div class="cdek-city-suggestions"></div>
				</div>
				<div class="open-pvz-btn" data-city="" style="display: none;">
					<script type="application/cdek-offices">[]</script>
					<a><?php esc_html_e( 'Выбрать пункт выдачи', 'flavor' ); ?></a>
				</div>
				<input name="office_code" class="cdek-office-code" type="hidden" value="">
				<div class="cdek-delivery-cost" style="display: none;">
					<span class="cdek-delivery-cost__label">Стоимость доставки:</span>
					<span class="cdek-delivery-cost__val"></span>
					<span class="cdek-delivery-cost__days"></span>
				</div>
				<input type="hidden" name="cdek_city_code" value="">
				<input type="hidden" name="cdek_delivery_cost" value="">
				<input type="hidden" name="cdek_tariff_code" value="">
			</div>
		</div>

		<div class="checkout-delivery-panel" data-delivery-panel="pickup" style="display: none;">
			<div class="checkout-pickup-info">
				<p>Самовывоз со склада по адресу: ул. Дружбы 5-40, г. Альметьевск, респ. Татарстан, Россия, 423453</p>
			</div>
		</div>

		<div class="checkout-fields checkout-fields--extra">
			<input type="text" name="recipient" placeholder="ФИО получателя">
			<textarea name="comment" placeholder="Комментарий к заказу" rows="4"></textarea>
		</div>

		<label class="checkbox">
			<input type="checkbox" name="personal_data">
			<span class="check"></span>
			<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ «О персональных данных»</span>
		</label>

		<!-- Total -->
		<div class="checkout-total">
			<div class="checkout-total__header">
				<span>Итоговая сумма</span>
				<span class="checkout-total__val"><?php echo wp_kses_post( $cart_total ); ?></span>
				<button class="checkout-total__toggle">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M8 6L12 10H4L8 6Z" fill="black"/>
					</svg>
				</button>
			</div>
			<div class="checkout-total__details">
				<div class="checkout-total__row">
					<span>Стоимость заказа</span>
					<span><?php echo wp_kses_post( $cart_subtotal ); ?></span>
				</div>
				<div class="checkout-total__row">
					<span>Доставка</span>
					<span>—</span>
				</div>
			</div>
		</div>

		<button class="btn checkout-submit" data-buyer-type="physical">Заказать</button>
	</div>

	<!-- Legal Person Form -->
	<div class="checkout-buyer-panel" data-buyer-panel="legal" style="display: none;">
		<div class="checkout-fields">
			<input type="text" name="lastname" value="<?php echo esc_attr( $last_name ); ?>" placeholder="Фамилия">
			<input type="text" name="firstname" value="<?php echo esc_attr( $first_name ); ?>" placeholder="Имя">
			<input type="text" name="patronymic" value="<?php echo esc_attr( $surname ); ?>" placeholder="Отчество">
			<input type="email" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="Email">
			<input type="tel" name="phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="Телефон">
		</div>

		<div class="checkout-fields">
			<div class="checkout-select">
				<select name="legal_entity">
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
			<input type="text" name="inn" placeholder="ИНН Организации или ИП">
			<input type="text" name="kpp" placeholder="КПП Организации или ИП">
			<textarea name="comment" placeholder="Комментарий к заказу" rows="4"></textarea>
		</div>

		<!-- File Attachments -->
		<div class="checkout-attachments">
			<div class="checkout-attachment" style="display: none;">
				<span class="checkout-attachment__name"></span>
				<button class="checkout-attachment__delete">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M20 5C20 5.55228 19.5523 6 19 6H18.997L18.064 19.142C18.0281 19.6466 17.8023 20.1188 17.4321 20.4636C17.0619 20.8083 16.5749 20.9999 16.069 21H7.93C7.42414 20.9999 6.93707 20.8083 6.56688 20.4636C6.19669 20.1188 5.97093 19.6466 5.935 19.142L5.003 6H5C4.44772 6 4 5.55228 4 5C4 4.44772 4.44772 4 5 4H9C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4H19C19.5523 4 20 4.44772 20 5ZM7.003 6L7.931 19H16.069L16.997 6H7.003Z" fill="black"/>
					</svg>
				</button>
			</div>
			<label class="btn btn-outline checkout-attach-btn">
				<input type="file" name="requisites" style="display: none;">
				Прикрепить реквизиты
			</label>
		</div>

		<label class="checkbox">
			<input type="checkbox" name="authorized">
			<span class="check"></span>
			<span class="label">Я подтверждаю, что уполномочен представлять интересы данного юридического лица или ИП</span>
		</label>
		<label class="checkbox">
			<input type="checkbox" name="personal_data">
			<span class="check"></span>
			<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ «О персональных данных»</span>
		</label>

		<button class="btn checkout-submit" data-buyer-type="legal">Выставить счёт</button>
	</div>
</div>

<?php endif; ?>
