<?php
/*
 * Template Name: Авторизация
 */
get_header();
?>

<main class="main-page">
	<section class="top-block" style="padding-top: 60px; padding-bottom: 60px; min-height: calc(100vh - 140px);">
		<div class="container">
			<div class="authorization">
				<div class="title">Авторизация</div>

				<?php if ( isset( $_GET['login_error'] ) ) : ?>
					<div class="error-message" style="color: #E20000; padding: 12px; margin-bottom: 16px; border: 1px solid #E20000;">
						<?php echo esc_html( urldecode( $_GET['login_error'] ) ); ?>
					</div>
				<?php endif; ?>

				<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post" class="authorization">
					<?php wp_nonce_field( 'titan_login', 'titan_login_nonce' ); ?>
					<input type="text" name="email" placeholder="Почта" required>

					<label class="password">
						<input type="password" name="password" placeholder="Пароль" minlength="6" maxlength="24" required>
						<div class="visible__text"></div>
					</label>

					<input type="submit" value="Войти">
				</form>

				<div class="action__block">
					<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Регистрация' ) ) ); ?>">Регистрация</a>
					<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Восстановление пароля' ) ) ); ?>">Забыли пароль?</a>
				</div>
			</div>
		</div>
	</section>
</main>

<script>
jQuery(function($) {
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

<?php get_footer(); ?>
