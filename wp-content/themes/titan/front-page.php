<?php get_header(); ?>

<main class="main-page">
	<section class="top-block">
		<div class="container">
			<div class="top-block-inner grid">
				<div class="text-block">
					<div class="title"><h1>Наши услуги</h1></div>
					<div class="list">
						<ul>
							<li>разработка электронных устройств и встраиваемых систем;</li>
							<li>контрактное производство электроники.</li>
						</ul>
					</div>
					<div class="btn-block">
						<a href="#" class="btn">Разработка электроники</a>
						<a href="#" class="btn">Производство электроники</a>
					</div>
				</div>
				<div class="img">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/top-img.png' ); ?>" alt="Наши услуги">
				</div>
			</div>
		</div>
	</section>

	<section class="slogan-block">
		<div class="container">
			<div class="text-block">
				<div class="title">
					<h3>Наш принцип:</h3>
				</div>
				<div class="text">Ваша идея - наша реализация</div>
			</div>
		</div>
	</section>

	<section class="contacts-form">
		<div class="container">
			<div class="grid">
				<div class="title">
					<h3>Свяжитесь с нами</h3>
				</div>
				<div class="form-block">
					<?php echo do_shortcode( '[contact-form-7 title="Свяжитесь с нами"]' ); ?>
				</div>
			</div>
		</div>
		<div class="file-error">
			<div class="name"></div>
			<div class="error-text">Файл слишком большой</div>
		</div>
	</section>
</main>

<script>
jQuery(function($) {

	/* Маска имени — запрет цифр и спецсимволов (CF7 рендерит type="text", не type="name") */
	$(document).on('input', 'input[name="your-name"]', function() {
		this.value = this.value.replace(/[^а-яА-ЯёЁa-zA-Z\s]/g, '');
	});

	/* Гарантируем наличие элементов для CF7 валидации */
	$('.wpcf7 .screen-reader-response').each(function() {
		if (!$(this).find('p').length) {
			$(this).prepend('<p role="status" aria-live="polite" aria-atomic="true"></p>');
		}
		if (!$(this).find('ul').length) {
			$(this).append('<ul></ul>');
		}
	});

	/* Попап "Спасибо" при успешной отправке */
	document.addEventListener('wpcf7mailsent', function() {
		$('#thanx').fadeIn(300);
	}, false);

	/* Переинициализация Inputmask после сабмита CF7 */
	document.addEventListener('wpcf7submit', function() {
		if (typeof Inputmask !== 'undefined') {
			new Inputmask("+7 (999) 999 99-99").mask($('input[type="tel"]'));
		}
	}, false);

	/* Синхронизация визуального чекбокса с CF7 acceptance */
	$('.check-field .check, .check-field .label').on('click', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var $label = $(this).closest('label.checkbox');
		var $cb = $label.find('input[type="checkbox"]');
		var checked = !$cb.prop('checked');
		$cb.prop('checked', checked);
		$label.find('.check').toggleClass('checked', checked);
		/* Нативное событие — CF7 6.x слушает только его */
		$cb[0].dispatchEvent(new Event('change', { bubbles: true }));
	});

});
</script>

<?php get_footer(); ?>
