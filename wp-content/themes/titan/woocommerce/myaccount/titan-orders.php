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
				$order_number = $order->get_order_number();
				$order_date   = $order->get_date_created();
				$date_str     = $order_date ? $order_date->date_i18n( 'd.m.Y' ) : '';
				$item_count   = $order->get_item_count();
				$status       = titan_order_status_label( $order->get_status() );
				$tracking     = $order->get_meta( '_tracking_number' );
				$subtotal     = $order->get_subtotal();
				$total        = $order->get_total();
			?>
			<div class="orders-table__row grid">
				<div class="orders-table__cell num"><?php echo esc_html( $i ); ?></div>
				<div class="orders-table__cell order-id">
					<span class="order-id__number"><?php echo esc_html( $order_number ); ?></span>
					<span class="order-id__date">от <?php echo esc_html( $date_str ); ?></span>
				</div>
				<div class="orders-table__cell"><?php echo esc_html( $item_count ); ?></div>
				<div class="orders-table__cell status"><?php echo esc_html( $status ); ?></div>
				<div class="orders-table__cell">
					<?php if ( $tracking ) : ?>
						<a href="#"><?php echo esc_html( $tracking ); ?></a>
					<?php else : ?>
						—
					<?php endif; ?>
				</div>
				<div class="orders-table__cell"><?php echo wp_kses_post( wc_price( $subtotal ) ); ?></div>
				<div class="orders-table__cell"><?php echo wp_kses_post( wc_price( $total ) ); ?></div>
			</div>
			<?php $i++; endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

	<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn orders-section__btn">Перейти в магазин</a>
</div>
