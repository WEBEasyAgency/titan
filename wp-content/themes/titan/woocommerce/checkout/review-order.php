<?php
/**
 * Review order — custom Titan template
 *
 * Only renders shipping methods. Totals are rendered as a separate AJAX fragment
 * via titan_checkout_totals_html() + woocommerce_update_order_review_fragments filter,
 * so that extra fields (recipient, comment) can be placed between shipping and totals.
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

	<?php if ( WC()->cart->needs_shipping() ) : ?>
		<div class="checkout-shipping">
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<?php
			$shipping_packages = WC()->shipping()->get_packages();
			$has_rates = false;
			foreach ( $shipping_packages as $pkg ) {
				if ( ! empty( $pkg['rates'] ) ) {
					$has_rates = true;
					break;
				}
			}
			if ( $has_rates ) : ?>
				<table class="checkout-shipping__table"><tbody>
					<?php wc_cart_totals_shipping_html(); ?>
				</tbody></table>
			<?php elseif ( ! empty( $shipping_packages ) ) : ?>
				<p class="checkout-shipping__notice"><?php esc_html_e( 'Нет доступных вариантов доставки для указанного города.', 'titan' ); ?></p>
			<?php else : ?>
				<p class="checkout-shipping__notice"><?php esc_html_e( 'Укажите город доставки для расчёта стоимости.', 'titan' ); ?></p>
			<?php endif; ?>
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

</div>
