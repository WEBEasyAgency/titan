<?php
/**
 * My Account — Orders tab (Заказы)
 * Shows active (current) orders.
 */
defined( 'ABSPATH' ) || exit;

$orders = wc_get_orders( array(
	'customer_id' => get_current_user_id(),
	'status'      => array( 'wc-pending', 'wc-processing', 'wc-on-hold' ),
	'limit'       => -1,
	'orderby'     => 'date',
	'order'       => 'DESC',
) );
?>

<div class="orders-section">
	<h2 class="orders-section__title">Заказы</h2>

	<?php if ( empty( $orders ) ) : ?>
		<p>У вас пока нет активных заказов.</p>
	<?php else : ?>
	<div class="orders-table">
		<div class="orders-table__head grid">
			<div class="orders-table__cell">№</div>
			<div class="orders-table__cell">№ Заказа</div>
			<div class="orders-table__cell">Кол-во товаров</div>
			<div class="orders-table__cell">Статус</div>
			<div class="orders-table__cell">Трек номер</div>
			<div class="orders-table__cell">Сумма</div>
			<div class="orders-table__cell">Итого с доставкой</div>
		</div>
		<div class="orders-table__body">
			<?php $i = 1; foreach ( $orders as $order ) :
				$order_id     = $order->get_id();
				$order_date   = $order->get_date_created();
				$date_str     = $order_date ? $order_date->date_i18n( 'd.m.Y' ) : '';
				$item_count   = $order->get_item_count();
				$status       = titan_order_status_label( $order->get_status() );
				$subtotal     = $order->get_subtotal();
				$total        = $order->get_total();
				$buyer_type   = $order->get_meta( '_buyer_type' );

				// Determine if delivery is via CDEK (not local pickup and not legal entity).
				$is_pickup = false;
				$is_legal  = $buyer_type === 'legal';
				foreach ( $order->get_shipping_methods() as $method ) {
					if ( strpos( $method->get_method_id(), 'local_pickup' ) !== false ) {
						$is_pickup = true;
					}
				}
				$has_delivery = ! $is_pickup && ! $is_legal;

				// Get CDEK tracking number from plugin meta.
				$cdek_tracking = '';
				if ( $has_delivery ) {
					$cdek_data = $order->get_meta( 'order_data' );
					if ( is_string( $cdek_data ) ) {
						$cdek_data = json_decode( $cdek_data, true );
					}
					if ( is_array( $cdek_data ) && ! empty( $cdek_data['number'] ) ) {
						$cdek_tracking = $cdek_data['number'];
					}
				}
			?>
			<div class="orders-table__row grid">
				<div class="orders-table__cell num"><?php echo esc_html( $i ); ?></div>
				<div class="orders-table__cell order-id">
					<?php if ( $has_delivery && $cdek_tracking ) : ?>
						<span class="order-id__number"><?php echo esc_html( $cdek_tracking ); ?></span>
						<span class="order-id__date">от <?php echo esc_html( $date_str ); ?></span>
					<?php else : ?>
						<span class="order-id__number">—</span>
						<span class="order-id__date">от <?php echo esc_html( $date_str ); ?></span>
					<?php endif; ?>
				</div>
				<div class="orders-table__cell"><?php echo esc_html( $item_count ); ?></div>
				<div class="orders-table__cell status"><?php echo esc_html( $status ); ?></div>
				<div class="orders-table__cell">
					<?php if ( $has_delivery && $cdek_tracking ) : ?>
						<a href="#"><?php echo esc_html( $cdek_tracking ); ?></a>
					<?php else : ?>
						—
					<?php endif; ?>
				</div>
				<div class="orders-table__cell"><?php echo wp_kses_post( wc_price( $subtotal ) ); ?></div>
				<div class="orders-table__cell">
					<?php if ( $has_delivery ) : ?>
						<?php echo wp_kses_post( wc_price( $total ) ); ?>
					<?php else : ?>
						—
					<?php endif; ?>
				</div>
			</div>
			<?php $i++; endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

	<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn orders-section__btn">Перейти в магазин</a>
</div>
