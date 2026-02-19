<script>
jQuery(function($) {

	// ============ AJAX Add to Cart ============
	$(document).on('click', '.titan-add-to-cart', function(e) {
		e.preventDefault();
		var $btn = $(this);
		var productId = $btn.data('product-id');
		var $qtyInput = $btn.closest('.btn-block').find('.quantity-block .number');
		var quantity = $qtyInput.length ? parseInt($qtyInput.val()) || 1 : 1;

		if ($btn.hasClass('loading')) return;
		$btn.addClass('loading');
		var origText = $btn.text();
		$btn.text('...');

		$.post(titan_wc.ajax_url, {
			action: 'titan_add_to_cart',
			nonce: titan_wc.nonce,
			product_id: productId,
			quantity: quantity
		}, function(response) {
			if (response.success) {
				$btn.text('В корзине');
				$('.cart-count').text(response.data.cart_count);
				setTimeout(function() {
					$btn.text(origText).removeClass('loading');
				}, 2000);
			} else {
				$btn.text('Ошибка');
				setTimeout(function() {
					$btn.text(origText).removeClass('loading');
				}, 2000);
			}
		}).fail(function() {
			$btn.text(origText).removeClass('loading');
		});
	});

	// ============ AJAX Product Search ============
	var searchTimer;
	$(document).on('input', '#titan-search-input', function() {
		var term = $(this).val();
		clearTimeout(searchTimer);

		if (term.length < 3) {
			$('#titan-search-results').hide().empty();
			return;
		}

		searchTimer = setTimeout(function() {
			$.post(titan_wc.ajax_url, {
				action: 'titan_product_search',
				nonce: titan_wc.nonce,
				term: term
			}, function(response) {
				var $results = $('#titan-search-results');
				$results.empty();

				if (response.success && response.data.length) {
					$.each(response.data, function(i, item) {
						$results.append(
							'<div class="item">' +
								'<div class="img"><a href="' + item.url + '"><img src="' + item.img + '" alt=""></a></div>' +
								'<div class="name"><a href="' + item.url + '">' + item.name + '</a></div>' +
								'<div class="price">' + item.price + '</div>' +
							'</div>'
						);
					});
					$results.show();
				} else {
					$results.hide();
				}
			});
		}, 300);
	});

	$(document).on('submit', '#titan-product-search-form', function(e) {
		e.preventDefault();
	});

	// ============ Cart Page: AJAX Quantity Updates ============
	var cartTimer;
	$(document).on('click', '.cart-page .quantity-block .minus, .cart-page .quantity-block .plus', function() {
		var $row = $(this).closest('.t-row');
		var key = $row.data('cart-item-key');
		var qty = parseInt($row.find('.number').val());

		clearTimeout(cartTimer);
		cartTimer = setTimeout(function() {
			$.post(titan_wc.ajax_url, {
				action: 'titan_update_cart',
				nonce: titan_wc.nonce,
				cart_item_key: key,
				quantity: qty
			}, function(response) {
				if (response.success) {
					$('#titan-cart-total').html(response.data.cart_total);
					$('.cart-count').text(response.data.cart_count);
					if (qty <= 0) {
						$row.fadeOut(300, function() { $(this).remove(); });
					}
				}
			});
		}, 500);
	});

	// ============ Cart Page: Remove Item ============
	$(document).on('click', '.titan-remove-cart-item', function(e) {
		e.preventDefault();
		var $row = $(this).closest('.t-row');
		var key = $row.data('cart-item-key');

		$.post(titan_wc.ajax_url, {
			action: 'titan_update_cart',
			nonce: titan_wc.nonce,
			cart_item_key: key,
			quantity: 0
		}, function(response) {
			if (response.success) {
				$row.fadeOut(300, function() { $(this).remove(); });
				$('#titan-cart-total').html(response.data.cart_total);
				$('.cart-count').text(response.data.cart_count);
				if (response.data.cart_count === 0) {
					location.reload();
				}
			}
		});
	});

	// ============ Cart Page: Clear All ============
	$(document).on('click', '#titan-clear-cart', function(e) {
		e.preventDefault();
		var keys = [];
		$('.cart-table .t-row').each(function() {
			keys.push($(this).data('cart-item-key'));
		});

		var completed = 0;
		$.each(keys, function(i, key) {
			$.post(titan_wc.ajax_url, {
				action: 'titan_update_cart',
				nonce: titan_wc.nonce,
				cart_item_key: key,
				quantity: 0
			}, function() {
				completed++;
				if (completed >= keys.length) {
					location.reload();
				}
			});
		});
	});

	// ============ Request Popup: Fill product info ============
	$(document).on('click', '.popup[data-product-name]', function() {
		var name = $(this).data('product-name');
		$('#request-product .product-info .name, #request-product .popup-name').text(name);
	});
});
</script>
