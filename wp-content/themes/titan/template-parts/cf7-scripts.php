<script>
jQuery(function($) {
	/* Маска имени */
	$(document).on('input', 'input[name="your-name"]', function() {
		this.value = this.value.replace(/[^а-яА-ЯёЁa-zA-Z\s]/g, '');
	});

	/* CF7 валидация DOM */
	$('.wpcf7 .screen-reader-response').each(function() {
		if (!$(this).find('p').length) $(this).prepend('<p role="status" aria-live="polite" aria-atomic="true"></p>');
		if (!$(this).find('ul').length) $(this).append('<ul></ul>');
	});

	/* Попап "Спасибо" */
	document.addEventListener('wpcf7mailsent', function() {
		$('#thanx').fadeIn(300);
	}, false);

	/* Переинициализация Inputmask */
	document.addEventListener('wpcf7submit', function() {
		if (typeof Inputmask !== 'undefined') {
			new Inputmask("+7 (999) 999 99-99").mask($('input[type="tel"]'));
		}
	}, false);

	/* Чекбокс acceptance */
	$('.check-field .check, .check-field .label').on('click', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var $label = $(this).closest('label.checkbox');
		var $cb = $label.find('input[type="checkbox"]');
		var checked = !$cb.prop('checked');
		$cb.prop('checked', checked);
		$label.find('.check').toggleClass('checked', checked);
		$cb[0].dispatchEvent(new Event('change', { bubbles: true }));
	});
});
</script>
