<?php
/*
 * Template Name: Восстановление пароля
 */
get_header();
?>

<main class="main-page">
	<section class="top-block" style="padding-top: 60px; padding-bottom: 60px; min-height: calc(100vh - 140px);">
		<div class="container">
			<?php if ( isset( $_GET['recovery_success'] ) ) : ?>
				<div class="recovery__completed">
					<div class="title">Вам отправлено письмо с ссылкой для восстановления пароля.<br> Используйте его для сброса пароля</div>
					<div class="sub__title">Если вы не получили письмо, пожалуйста, проверьте папку «Спам».</div>

					<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Авторизация' ) ) ); ?>" class="btn">Войти</a>
				</div>
			<?php else : ?>
				<div class="recovery">
					<div class="title">Восстановление пароля</div>
					<div class="sub__title">Введите почту, которую вы использовали при регистрации.</div>

					<?php if ( isset( $_GET['recovery_error'] ) ) : ?>
						<div class="error-message" style="color: #E20000; padding: 12px; margin-bottom: 16px; border: 1px solid #E20000;">
							<?php echo esc_html( urldecode( $_GET['recovery_error'] ) ); ?>
						</div>
					<?php endif; ?>

					<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post" class="recovery">
						<?php wp_nonce_field( 'titan_recovery', 'titan_recovery_nonce' ); ?>
						<input type="text" name="email" placeholder="Почта" required>
						<input type="submit" value="Восстановить">
					</form>

					<div class="action__block">
						<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Регистрация' ) ) ); ?>">Регистрация</a>
						<a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Авторизация' ) ) ); ?>">Войти</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
