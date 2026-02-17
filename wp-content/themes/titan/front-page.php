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
document.addEventListener('wpcf7mailsent', function() {
	jQuery('#thanx').fadeIn(300);
}, false);

document.addEventListener('wpcf7submit', function() {
	var selector = jQuery('input[type="tel"]');
	if (typeof Inputmask !== 'undefined') {
		var im = new Inputmask("+7 (999) 999 99-99");
		im.mask(selector);
	}
}, false);

/* Синхронизация визуального чекбокса с CF7 acceptance */
jQuery(function($) {
	$('.check-field label.checkbox').on('click', function() {
		var $cb = $(this).find('input[type="checkbox"]');
		setTimeout(function() {
			var $check = $cb.closest('label.checkbox').find('.check');
			if ($cb.is(':checked')) {
				$check.addClass('checked');
			} else {
				$check.removeClass('checked');
			}
		}, 10);
	});
});
</script>

<?php get_footer(); ?>
