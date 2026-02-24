<div class="cookies" style="display: none;">
	<div class="close">
		<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M2 2L14 14M2 14L14 2" stroke="#A7A7A7" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round"/>
		</svg>
	</div>
	<div class="text-block">
		<div class="title">
			<h4>Мы используем cookie</h4>
		</div>
		<div class="text">Используя сайт, вы соглашаетесь на обработку данных в Cookies для корректной работы сайта, вашей персонализации и других целей, предусмотренных Политикой.</div>
		<div class="btn-block"><a href="#" class="btn ok-btn">Принимаю</a></div>
	</div>
</div>

<script>
jQuery(function($) {
	// Check if cookie consent was given
	function getCookie(name) {
		var matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}

	function setCookie(name, value, days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + (value || "") + expires + "; path=/";
	}

	// Show cookies banner if not accepted
	if (!getCookie('titan_cookies_accepted')) {
		$('.cookies').fadeIn(300);
	}

	// Accept cookies
	$('.cookies .ok-btn').on('click', function(e) {
		e.preventDefault();
		setCookie('titan_cookies_accepted', 'yes', 365);
		$(this).parents('.cookies').fadeOut(300);
	});

	// Close without accepting
	$('.cookies .close').on('click', function() {
		$(this).parents('.cookies').fadeOut(300);
	});
});
</script>
