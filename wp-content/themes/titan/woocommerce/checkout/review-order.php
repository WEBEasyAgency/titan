<?php
/**
 * Review order — custom Titan template
 *
 * Replaces default WooCommerce review-order.php table with div-based layout.
 * Product listing is omitted (rendered separately in form-checkout.php).
 * Shipping methods use a table wrapper for WC compatibility (wc_cart_totals_shipping_html outputs <tr>).
 * Totals use the custom "Итоговая сумма" collapsible block.
 *
 * IMPORTANT: Root element MUST have class `woocommerce-checkout-review-order-table`
 * because WC's update_order_review AJAX uses this selector for replaceWith().
 *
 * @see woocommerce/templates/checkout/review-order.php (original)
 * @version 5.2.0
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-checkout-review-order-table">

	<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>
	<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
		<div class="checkout-shipping">
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<table class="checkout-shipping__table"><tbody>
				<?php wc_cart_totals_shipping_html(); ?>
			</tbody></table>
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

	<div class="checkout-total">
		<div class="checkout-total__header">
			<span>Итоговая сумма</span>
			<span class="checkout-total__val"><?php wc_cart_totals_order_total_html(); ?></span>
			<button type="button" class="checkout-total__toggle">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8 6L12 10H4L8 6Z" fill="black"/>
				</svg>
			</button>
		</div>
		<div class="checkout-total__details">
			<div class="checkout-total__row">
				<span>Стоимость заказа</span>
				<span><?php wc_cart_totals_subtotal_html(); ?></span>
			</div>
			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<div class="checkout-total__row">
					<span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
					<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
				</div>
			<?php endforeach; ?>
			<div class="checkout-total__row">
				<span>Доставка</span>
				<span class="checkout-total__delivery-val"><?php echo wp_kses_post( WC()->cart->get_cart_shipping_total() ); ?></span>
			</div>
			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<div class="checkout-total__row">
					<span><?php echo esc_html( $fee->name ); ?></span>
					<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
				</div>
			<?php endforeach; ?>
			<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
				<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
						<div class="checkout-total__row">
							<span><?php echo esc_html( $tax->label ); ?></span>
							<span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="checkout-total__row">
						<span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
						<span><?php wc_cart_totals_taxes_total_html(); ?></span>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>

	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

</div>
