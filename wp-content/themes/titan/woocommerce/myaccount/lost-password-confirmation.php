<?php
/**
 * Lost password confirmation text.
 * Custom layout matching Titan design
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */
defined( 'ABSPATH' ) || exit;
?>

<div class="recovery__completed">
	<div class="title">Вам отправлено письмо с временным паролем.<br>Используйте его для авторизации</div>
	<div class="sub__title">Если вы не получили письмо, пожалуйста, проверьте папку «Спам».</div>
	<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn">Войти</a>
</div>
