<script>
jQuery(function($) {

	function updateCartBadge(count) {
		$('.cart-count').text(count).toggleClass('hidden', parseInt(count) < 1);
	}

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
				updateCartBadge(response.data.cart_count);
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

	// Close search results on click outside
	$(document).on('mouseup', function(e) {
		var $block = $('.search-block');
		if (!$block.is(e.target) && $block.has(e.target).length === 0) {
			$('#titan-search-results').hide();
		}
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
					updateCartBadge(response.data.cart_count);
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
				updateCartBadge(response.data.cart_count);
				if (response.data.cart_count === 0) {
					location.reload();
				}
			}
		});
	});

	// ============ Cart Page: Clear All ============
	$(document).on('click', '#titan-clear-cart', function(e) {
		e.preventDefault();
		$.post(titan_wc.ajax_url, {
			action: 'titan_clear_cart',
			nonce: titan_wc.nonce
		}, function() {
			location.reload();
		});
	});

	// ============ Catalog Sidebar: AJAX Filter ============
	function titanFilterCatalog() {
		var $checked = $('.category-list input[type="radio"]:checked');
		var catId = parseInt($checked.data('cat-id')) || 0;
		var subcatIds = [];

		if (catId > 0) {
			var $parent = $checked.closest('.item.parent');
			if ($parent.length) {
				var $allCheckbox = $parent.find('input[data-select-all]');
				if (!$allCheckbox.prop('checked')) {
					$parent.find('.subcategories input[type="checkbox"]:checked').not('[data-select-all]').each(function() {
						var id = parseInt($(this).data('subcat-id'));
						if (id > 0) subcatIds.push(id);
					});
				}
			}
		}

		$('.catalog-table').css('opacity', '0.5');

		$.post(titan_wc.ajax_url, {
			action: 'titan_filter_catalog',
			nonce: titan_wc.nonce,
			category_id: catId,
			subcategory_ids: subcatIds
		}, function(response) {
			$('.catalog-grid .catalog-table').replaceWith(response);
		}).fail(function() {
			$('.catalog-table').css('opacity', '1');
		});
	}

	// Radio: select category
	$(document).on('change', '.category-list input[type="radio"]', function() {
		var $parent = $(this).closest('.item.parent');
		if ($parent.length) {
			$parent.find('input[data-select-all]').prop('checked', true);
			$parent.find('.subcategories input[type="checkbox"]').not('[data-select-all]').prop('checked', false);
		}
		titanFilterCatalog();
	});

	// Checkbox "Все": reset subcategories, show all products of category
	$(document).on('change', '.subcategories input[data-select-all]', function() {
		$(this).prop('checked', true);
		$(this).closest('.subcategories').find('input[type="checkbox"]').not(this).prop('checked', false);
		titanFilterCatalog();
	});

	// Checkbox: individual subcategory
	$(document).on('change', '.subcategories input[type="checkbox"]:not([data-select-all])', function() {
		var $subs = $(this).closest('.subcategories');
		var $all = $subs.find('input[data-select-all]');
		var anyChecked = $subs.find('input[type="checkbox"]:checked').not('[data-select-all]').length > 0;
		if (anyChecked) {
			$all.prop('checked', false);
		} else {
			$all.prop('checked', true);
		}
		titanFilterCatalog();
	});

	// ============ Request Popup: Fill product info ============
	$(document).on('click', '.popup[data-product-name]', function() {
		var name = $(this).data('product-name');
		var price = $(this).data('product-price');
		$('#request-product .product-info .name').text(name);
		$('#request-product .product-info .price').text(price ? price + ' ₽' : '');
	});

	// ============ Request Popup: AJAX Submit ============
	$(document).on('submit', '#request-product form', function(e) {
		e.preventDefault();
		var $form = $(this);
		var $btn = $form.find('button[type="submit"]');

		if ($btn.hasClass('loading')) return;
		$btn.addClass('loading');
		var origText = $btn.text();
		$btn.text('...');

		$.post(titan_wc.ajax_url, {
			action: 'titan_request_product',
			nonce: titan_wc.nonce,
			name: $form.find('input[type="name"]').val(),
			phone: $form.find('input[type="tel"]').val(),
			email: $form.find('input[type="email"]').val(),
			product_name: $('#request-product .product-info .name').text(),
			product_price: $('#request-product .product-info .price').text().replace(' ₽', ''),
			quantity: $form.find('.number').val() || 1
		}, function(response) {
			$btn.text(origText).removeClass('loading');
			if (response.success) {
				$form[0].reset();
				$form.find('.number').val('1');
				$('#request-product').fadeOut(300, function() {
					$('#thanx').fadeIn(300);
				});
			}
		}).fail(function() {
			$btn.text(origText).removeClass('loading');
		});
	});
});
</script>
