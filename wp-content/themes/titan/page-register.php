<?php
/*
 * Template Name: Регистрация
 */
get_header();
?>

<main class="main-page">
	<section class="top-block">
		<div class="container">
			<div class="registration">
				<div class="title">Регистрация</div>

				<?php if ( isset( $_GET['register_error'] ) ) : ?>
					<div class="error-message" style="color: #E20000; padding: 12px; margin-bottom: 16px; border: 1px solid #E20000;">
						<?php echo esc_html( urldecode( $_GET['register_error'] ) ); ?>
					</div>
				<?php endif; ?>

				<div class="tabs__type">
					<div class="tabs__header">
						<div class="tabs__header-item active" data-tab="1">Физическое лицо</div>
						<div class="tabs__header-item" data-tab="2">Юридическое лицо</div>
					</div>

					<div class="tabs__body">
						<!-- Individual -->
						<div class="tabs__body-item active" data-tab="1">
							<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
								<?php wp_nonce_field( 'titan_register', 'titan_register_nonce' ); ?>
								<input type="hidden" name="user_type" value="individual">

								<input type="text" name="lastname" placeholder="Фамилия" required>
								<input type="text" name="name" placeholder="Имя" required>
								<input type="text" name="surname" placeholder="Отчество" required>
								<input type="text" name="email" placeholder="Почта" required>
								<input type="tel" name="tel" placeholder="Телефон" required>

								<label class="password">
									<input type="password" name="password" placeholder="Пароль" minlength="6" maxlength="24" required>
									<div class="visible__text"></div>
								</label>

								<label class="password repeat">
									<input type="password" name="password2" placeholder="Повторите пароль" minlength="6" maxlength="24" required>
									<div class="visible__text"></div>
								</label>

								<label class="checkbox">
									<input type="checkbox" name="check" required>
									<span class="check"></span>
									<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ "О персональных данных"</span>
								</label>

								<input type="submit" value="Зарегистрироваться">

								<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'login' ) ) ); ?>" class="to_login">У меня есть аккаунт. Войти</a>
							</form>
						</div>

						<!-- Business -->
						<div class="tabs__body-item" data-tab="2">
							<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
								<?php wp_nonce_field( 'titan_register', 'titan_register_nonce' ); ?>
								<input type="hidden" name="user_type" value="business">

								<input type="text" name="lastname" placeholder="Фамилия" required>
								<input type="text" name="name" placeholder="Имя" required>
								<input type="text" name="surname" placeholder="Отчество" required>
								<input type="text" name="email" placeholder="Почта" required>
								<input type="tel" name="tel" placeholder="Телефон" required>

								<input type="text" name="inn" placeholder="ИНН Организации или ИП" required>
								<input type="text" name="organization" placeholder="Наименование организации" required>

								<label class="password">
									<input type="password" name="password" placeholder="Пароль" minlength="6" maxlength="24" required>
									<div class="visible__text"></div>
								</label>

								<label class="password repeat">
									<input type="password" name="password2" placeholder="Повторите пароль" minlength="6" maxlength="24" required>
									<div class="visible__text"></div>
								</label>

								<label class="checkbox">
									<input type="checkbox" name="check_auth" required>
									<span class="check"></span>
									<span class="label">Я подтверждаю, что уполномочен представлять интересы данного юридического лица или ИП</span>
								</label>

								<label class="checkbox">
									<input type="checkbox" name="check" required>
									<span class="check"></span>
									<span class="label">Согласен на обработку персональных данных согласно ФЗ от 27 июля 2006 г. № 152-ФЗ "О персональных данных"</span>
								</label>

								<input type="submit" value="Зарегистрироваться">

								<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'login' ) ) ); ?>" class="to_login">У меня есть аккаунт. Войти</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<script>
jQuery(function($) {
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

<?php get_footer(); ?>
