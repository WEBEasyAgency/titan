<?php
/**
 * My Account page — custom layout with sidebar.
 * Overrides WooCommerce default my-account.php
 */
defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
	wc_get_template( 'myaccount/form-login.php' );
	return;
}

// Determine which endpoint is active
$current_endpoint = WC()->query->get_current_endpoint();

$account_url = wc_get_page_permalink( 'myaccount' );

$menu_items = array(
	''               => array( 'label' => 'Профиль',            'url' => $account_url ),
	'titan-checkout' => array( 'label' => 'Оформление заказа',  'url' => wc_get_endpoint_url( 'titan-checkout', '', $account_url ) ),
	'titan-orders'   => array( 'label' => 'Заказы',             'url' => wc_get_endpoint_url( 'titan-orders', '', $account_url ) ),
	'titan-history'  => array( 'label' => 'История заказов',    'url' => wc_get_endpoint_url( 'titan-history', '', $account_url ) ),
	'logout'         => array( 'label' => 'Выйти',              'url' => wc_logout_url() ),
);
?>

<h1 class="account-page__title">Личный кабинет</h1>
<div class="account-page__inner">
	<aside class="account-sidebar">
		<?php foreach ( $menu_items as $key => $item ) :
			$is_active = ( $key === $current_endpoint );
			$class = 'account-sidebar__item';
			if ( $is_active ) {
				$class .= ' active';
			}
			if ( $key === 'logout' ) {
				$class .= ' account-sidebar__item--logout';
			}
		?>
			<a href="<?php echo esc_url( $item['url'] ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
		<?php endforeach; ?>
	</aside>
	<div class="account-content">
		<?php do_action( 'woocommerce_account_content' ); ?>
	</div>
</div>
