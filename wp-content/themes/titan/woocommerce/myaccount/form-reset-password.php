<?php
/**
 * WooCommerce Reset Password Form
 * Custom layout matching Titan design
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
?>

<div class="recovery">
	<div class="title">Установка нового пароля</div>
	<div class="sub__title">Введите новый пароль для вашего аккаунта.</div>

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

	<form method="post" class="recovery woocommerce-ResetPassword reset_password">

		<?php do_action( 'woocommerce_resetpassword_form_start' ); ?>

		<label class="password">
			<input type="password" name="password_1" placeholder="Новый пароль" autocomplete="new-password" minlength="6" required>
			<div class="visible__text"></div>
		</label>

		<label class="password repeat">
			<input type="password" name="password_2" placeholder="Повторите пароль" autocomplete="new-password" minlength="6" required>
			<div class="visible__text"></div>
		</label>

		<input type="hidden" name="reset_key" value="<?php echo esc_attr( $_GET['key'] ?? '' ); ?>" />
		<input type="hidden" name="reset_login" value="<?php echo esc_attr( $_GET['login'] ?? '' ); ?>" />

		<?php do_action( 'woocommerce_resetpassword_form' ); ?>

		<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

		<input type="submit" value="Сохранить новый пароль">

		<?php do_action( 'woocommerce_resetpassword_form_end' ); ?>

	</form>

	<div class="action__block">
		<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Войти</a>
	</div>
</div>

<script>
jQuery(function($) {
	// Password visibility
	$('.password').each(function() {
		var $container = $(this);
		var $input = $container.find('input[type="password"]');
		var $toggleBtn = $container.find('.visible__text');

		$toggleBtn.on('click', function() {
			if ($input.attr('type') === 'password') {
				$input.attr('type', 'text');
				$(this).addClass('active');
			} else {
				$input.attr('type', 'password');
				$(this).removeClass('active');
			}
		});
	});
});
</script>

<?php do_action( 'woocommerce_after_reset_password_form' ); ?>
