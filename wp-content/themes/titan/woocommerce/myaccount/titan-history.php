<?php
/**
 * My Account — History tab (История заказов)
 * Shows completed/cancelled/refunded orders with detail expansion.
 */
defined( 'ABSPATH' ) || exit;

$orders = wc_get_orders( array(
	'customer_id' => get_current_user_id(),
	'status'      => array( 'wc-completed', 'wc-cancelled', 'wc-refunded' ),
	'limit'       => -1,
	'orderby'     => 'date',
	'order'       => 'DESC',
) );
?>

<div class="history-section">
	<h2 class="history-section__title">История заказов</h2>

	<?php if ( empty( $orders ) ) : ?>
		<p>История заказов пуста.</p>
	<?php else : ?>
	<div class="history-table">
		<div class="history-table__head grid">
			<div class="history-table__cell">№</div>
			<div class="history-table__cell">№ Заказа</div>
			<div class="history-table__cell">Дата</div>
			<div class="history-table__cell">Сумма</div>
			<div class="history-table__cell">Итого с дос-й</div>
			<div class="history-table__cell">Статус</div>
			<div class="history-table__cell"></div>
			<div class="history-table__cell"></div>
		</div>
		<div class="history-table__body">
			<?php $i = 1; foreach ( $orders as $order ) :
				$order_id     = $order->get_id();
				$order_number = $order->get_order_number();
				$order_date   = $order->get_date_created();
				$date_str     = $order_date ? $order_date->date_i18n( 'd.m.Y' ) : '';
				$subtotal     = $order->get_subtotal();
				$total        = $order->get_total();
				$status       = titan_order_status_label( $order->get_status() );
				$buyer_type   = $order->get_meta( '_buyer_type' );
				$is_legal     = ( $buyer_type === 'legal' );
				$row_class    = $is_legal ? ' history-table__row--alt' : '';
				$items        = $order->get_items();
			?>
			<!-- Row -->
			<div class="history-table__row<?php echo esc_attr( $row_class ); ?> grid">
				<div class="history-table__cell num"><?php echo esc_html( $i ); ?></div>
				<div class="history-table__cell"><?php echo $is_legal ? '—' : esc_html( $order_number ); ?></div>
				<div class="history-table__cell align-right"><?php echo esc_html( $date_str ); ?></div>
				<div class="history-table__cell align-right"><?php echo $is_legal ? '—' : wp_kses_post( wc_price( $subtotal ) ); ?></div>
				<div class="history-table__cell align-right"><?php echo wp_kses_post( wc_price( $total ) ); ?></div>
				<div class="history-table__cell align-right"><?php echo esc_html( $status ); ?></div>
				<div class="history-table__cell actions"><a href="#" class="history-details-toggle">Подробнее</a></div>
				<div class="history-table__cell actions">
					<?php if ( $is_legal ) : ?>
						<a href="#" class="history-invoice-link">Посмотреть счёт</a>
					<?php endif; ?>
				</div>
			</div>
			<!-- Detail (hidden) -->
			<div class="history-table__detail" style="display: none;">
				<div class="history-detail-table">
					<div class="history-detail-table__head grid">
						<div class="history-detail-table__cell">Фото</div>
						<div class="history-detail-table__cell">Наименование</div>
						<div class="history-detail-table__cell">Цена за 1 ед.</div>
						<div class="history-detail-table__cell">Кол-во</div>
						<div class="history-detail-table__cell">Сумма</div>
					</div>
					<div class="history-detail-table__body">
						<?php foreach ( $items as $item ) :
							$product  = $item->get_product();
							if ( ! $product ) continue;
							$img_id   = $product->get_image_id();
							$img_url  = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : wc_placeholder_img_src( 'thumbnail' );
							$price    = $order->get_item_subtotal( $item, false, true );
							$qty      = $item->get_quantity();
							$line_total = $item->get_total();
						?>
						<div class="history-detail-table__row grid">
							<div class="history-detail-table__cell photo">
								<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
							</div>
							<div class="history-detail-table__cell name"><?php echo esc_html( $product->get_name() ); ?></div>
							<div class="history-detail-table__cell align-right"><?php echo wp_kses_post( wc_price( $price ) ); ?></div>
							<div class="history-detail-table__cell align-right"><?php echo esc_html( $qty ); ?></div>
							<div class="history-detail-table__cell align-right"><?php echo wp_kses_post( wc_price( $line_total ) ); ?></div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="history-detail-total">
					<span>Итого с доставкой</span>
					<span class="history-detail-total__val"><?php echo wp_kses_post( wc_price( $total ) ); ?></span>
				</div>
				<a href="#" class="history-detail-close">Закрыть</a>
			</div>

			<?php $i++; endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
</div>
