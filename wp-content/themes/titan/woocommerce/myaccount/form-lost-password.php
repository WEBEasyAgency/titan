<?php
/**
 * WooCommerce Lost Password Form
 * Custom layout matching Titan design
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>

<div class="recovery">
	<div class="title">Восстановление пароля</div>
	<div class="sub__title">Введите почту, которую вы использовали при регистрации.</div>

	<?php
	$notices = wc_get_notices();
	if ( ! empty( $notices['error'] ) ) : ?>
		<div class="error-message" style="color: #E20000; padding: 12px; margin-bottom: 16px; border: 1px solid #E20000;">
			<?php foreach ( $notices['error'] as $notice ) : ?>
				<?php echo wp_kses_post( $notice['notice'] ); ?><br>
			<?php endforeach; ?>
		</div>
	<?php
		wc_clear_notices();
	endif;
	?>

	<form method="post" class="recovery woocommerce-ResetPassword lost_reset_password">

		<?php do_action( 'woocommerce_lostpassword_form_start' ); ?>

		<input type="text" name="user_login" placeholder="Почта" autocomplete="username" required>

		<?php do_action( 'woocommerce_lostpassword_form' ); ?>

		<input type="hidden" name="wc_reset_password" value="true" />
		<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

		<input type="submit" value="Восстановить">

		<?php do_action( 'woocommerce_lostpassword_form_end' ); ?>

	</form>

	<div class="action__block">
		<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Регистрация</a>
		<?php endif; ?>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Войти</a>
	</div>
</div>

<?php do_action( 'woocommerce_after_lost_password_form' ); ?>
