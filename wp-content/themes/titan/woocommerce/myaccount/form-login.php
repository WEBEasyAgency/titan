<?php
/**
 * WooCommerce My Account - Login form
 * Custom layout matching Titan design
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="authorization">
	<div class="title">Авторизация</div>

	<?php if ( ! empty( $_GET['wc_error'] ) ) : ?>
		<div class="error-message" style="color: #E20000; padding: 12px; margin-bottom: 16px; border: 1px solid #E20000;">
			<?php echo esc_html( urldecode( $_GET['wc_error'] ) ); ?>
		</div>
	<?php endif; ?>

	<form class="authorization woocommerce-form woocommerce-form-login login" method="post">

		<?php do_action( 'woocommerce_login_form_start' ); ?>

		<input type="text" name="username" placeholder="Почта" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" autocomplete="username" required>

		<label class="password">
			<input type="password" name="password" placeholder="Пароль" autocomplete="current-password" required>
			<div class="visible__text"></div>
		</label>

		<?php do_action( 'woocommerce_login_form' ); ?>

		<label class="checkbox" style="display: flex; align-items: center; gap: 8px; margin: 16px 0;">
			<input type="checkbox" name="rememberme" value="forever">
			<span class="check"></span>
			<span class="label">Запомнить меня</span>
		</label>

		<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
		<input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" />
		<input type="submit" name="login" value="Войти">

		<?php do_action( 'woocommerce_login_form_end' ); ?>

	</form>

	<div class="action__block">
		<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">Забыли пароль?</a>
		<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
			<a href="#" class="show-register-form">Регистрация</a>
		<?php endif; ?>
	</div>
</div>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="registration" style="display: none; margin-top: 40px;">
	<div class="title">Регистрация</div>

	<div class="tabs__type">
		<div class="tabs__header">
			<div class="tabs__header-item active" data-tab="1">Физическое лицо</div>
			<div class="tabs__header-item" data-tab="2">Юридическое лицо</div>
		</div>

		<div class="tabs__body">
			<!-- Individual -->
			<div class="tabs__body-item active" data-tab="1">
				<form method="post" class="woocommerce-form woocommerce-form-register register">

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<input type="hidden" name="user_type" value="individual">

					<input type="text" name="billing_last_name" placeholder="Фамилия" required>
					<input type="text" name="billing_first_name" placeholder="Имя" required>
					<input type="text" name="surname" placeholder="Отчество">

					<input type="email" name="email" placeholder="Почта" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" autocomplete="email" required>
					<input type="tel" name="billing_phone" placeholder="Телефон">

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
						<label class="password">
							<input type="password" name="password" placeholder="Пароль" autocomplete="new-password" required>
							<div class="visible__text"></div>
						</label>
					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>

					<label class="checkbox">
						<input type="checkbox" name="terms" required>
						<span class="check"></span>
						<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ "О персональных данных"</span>
					</label>

					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<input type="submit" name="register" value="Зарегистрироваться">

					<a href="#" class="to_login show-login-form">У меня есть аккаунт. Войти</a>

					<?php do_action( 'woocommerce_register_form_end' ); ?>

				</form>
			</div>

			<!-- Business -->
			<div class="tabs__body-item" data-tab="2">
				<form method="post" class="woocommerce-form woocommerce-form-register register">

					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<input type="hidden" name="user_type" value="business">

					<input type="text" name="billing_last_name" placeholder="Фамилия" required>
					<input type="text" name="billing_first_name" placeholder="Имя" required>
					<input type="text" name="surname" placeholder="Отчество">

					<input type="email" name="email" placeholder="Почта" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" autocomplete="email" required>
					<input type="tel" name="billing_phone" placeholder="Телефон">

					<input type="text" name="inn" placeholder="ИНН Организации или ИП" required>
					<input type="text" name="billing_company" placeholder="Наименование организации" required>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
						<label class="password">
							<input type="password" name="password" placeholder="Пароль" autocomplete="new-password" required>
							<div class="visible__text"></div>
						</label>
					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>

					<label class="checkbox">
						<input type="checkbox" name="check_auth" required>
						<span class="check"></span>
						<span class="label">Я подтверждаю, что уполномочен представлять интересы данного юридического лица или ИП</span>
					</label>

					<label class="checkbox">
						<input type="checkbox" name="terms" required>
						<span class="check"></span>
						<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ "О персональных данных"</span>
					</label>

					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<input type="submit" name="register" value="Зарегистрироваться">

					<a href="#" class="to_login show-login-form">У меня есть аккаунт. Войти</a>

					<?php do_action( 'woocommerce_register_form_end' ); ?>

				</form>
			</div>
		</div>
	</div>
</div>

<?php endif; ?>

<script>
jQuery(function($) {
	// Toggle login/register forms
	$('.show-register-form').on('click', function(e) {
		e.preventDefault();
		$('.authorization').hide();
		$('.registration').show();
	});

	$('.show-login-form').on('click', function(e) {
		e.preventDefault();
		$('.registration').hide();
		$('.authorization').show();
	});

	// Tabs
	$('.tabs__header-item').on('click', function() {
		var id = $(this).data('tab');
		$('.tabs__header-item, .tabs__body-item').removeClass('active');
		$(this).addClass('active');
		$('.tabs__body-item[data-tab="' + id + '"]').addClass('active');
	});

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

	// Checkbox visual state
	$('.checkbox input[type="checkbox"]').on('change', function() {
		$(this).closest('.checkbox').find('.check').toggleClass('checked', $(this).is(':checked'));
	});
});
</script>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
