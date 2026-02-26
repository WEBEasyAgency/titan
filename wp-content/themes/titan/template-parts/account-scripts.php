<script>
jQuery(function($) {

	function updateCartBadge(count) {
		$('.cart-count').text(count).toggleClass('hidden', parseInt(count) < 1);
	}

	// ============ Profile: Change Password ============
	$('#btn-change-password').on('click', function() {
		$('#profile-view-actions').hide();
		$('#edit-profile-form').hide();
		$('#password-form').slideDown();
	});

	$('#btn-save-password').on('click', function() {
		var $form = $('#password-form');
		var current = $form.find('input[name="current_password"]').val();
		var newPass = $form.find('input[name="new_password"]').val();
		var confirm = $form.find('input[name="confirm_password"]').val();

		if (!current || !newPass || !confirm) {
			alert('Заполните все поля');
			return;
		}

		if (newPass !== confirm) {
			alert('Пароли не совпадают');
			return;
		}

		var $btn = $(this);
		$btn.prop('disabled', true).text('...');

		$.post(titan_wc.ajax_url, {
			action: 'titan_change_password',
			nonce: titan_wc.nonce,
			current_password: current,
			new_password: newPass,
			confirm_password: confirm
		}, function(response) {
			$btn.prop('disabled', false).text('Сохранить изменения');
			if (response.success) {
				$('#password-form').slideUp(function() {
					$('#profile-view-actions').show();
				});
				$form.find('input').val('');
				// Show success popup
				$('#popup-password-success').addClass('active');
			} else {
				alert(response.data || 'Ошибка');
			}
		}).fail(function() {
			$btn.prop('disabled', false).text('Сохранить изменения');
			alert('Ошибка сервера');
		});
	});

	// ============ Profile: Edit Profile ============
	$('#btn-edit-profile').on('click', function() {
		$('#profile-view-actions').hide();
		$('#password-form').hide();
		$('#edit-profile-form').slideDown();
	});

	$('#btn-cancel-edit').on('click', function() {
		$('#edit-profile-form').slideUp(function() {
			$('#profile-view-actions').show();
		});
	});

	$('#btn-save-profile').on('click', function() {
		var $form = $('#edit-profile-form');
		var $btn = $(this);

		$btn.prop('disabled', true).text('...');

		$.post(titan_wc.ajax_url, {
			action: 'titan_update_profile',
			nonce: titan_wc.nonce,
			last_name: $form.find('input[name="lastname"]').val(),
			first_name: $form.find('input[name="firstname"]').val(),
			surname: $form.find('input[name="patronymic"]').val(),
			phone: $form.find('input[name="phone"]').val()
		}, function(response) {
			$btn.prop('disabled', false).text('Сохранить изменения');
			if (response.success) {
				var d = response.data;
				$('.profile-card__name').text(d.full_name);
				$('.profile-card__email').text(d.email);
				$('.profile-card__phone').text(d.phone);
				$('.profile-card__type').text(d.user_type);

				$('#edit-profile-form').slideUp(function() {
					$('#profile-view-actions').show();
				});
			} else {
				alert(response.data || 'Ошибка');
			}
		}).fail(function() {
			$btn.prop('disabled', false).text('Сохранить изменения');
			alert('Ошибка сервера');
		});
	});

	// ============ Password visibility toggle ============
	$(document).on('click', '.password .visible__text', function() {
		var $input = $(this).siblings('input');
		if ($input.attr('type') === 'password') {
			$input.attr('type', 'text');
			$(this).addClass('active');
		} else {
			$input.attr('type', 'password');
			$(this).removeClass('active');
		}
	});

	// ============ Checkbox visual state ============
	$(document).on('change', '.checkbox input[type="checkbox"]', function() {
		$(this).closest('.checkbox').find('.check').toggleClass('checked', $(this).is(':checked'));
	});

	// ============ Legal Entities: Edit ============
	$(document).on('click', '.legal-entity__btn-edit', function() {
		var $entity = $(this).closest('.legal-entity');
		$entity.find('.legal-entity__edit-form').slideDown();
	});

	$(document).on('click', '.legal-entity__btn-cancel', function() {
		$(this).closest('.legal-entity__edit-form').slideUp();
	});

	$(document).on('click', '.legal-entity__btn-save', function() {
		var $entity = $(this).closest('.legal-entity');
		var $form = $entity.find('.legal-entity__edit-form');
		var entityId = $entity.data('id');
		var $btn = $(this);

		$btn.prop('disabled', true).text('...');

		$.post(titan_wc.ajax_url, {
			action: 'titan_legal_entity_save',
			nonce: titan_wc.nonce,
			entity_id: entityId,
			org_name: $form.find('input[name="org_name"]').val(),
			inn: $form.find('input[name="inn"]').val(),
			kpp: $form.find('input[name="kpp"]').val(),
			legal_address: $form.find('input[name="legal_address"]').val(),
			postal_code: $form.find('input[name="postal_code"]').val(),
			region: $form.find('input[name="region"]').val(),
			district: $form.find('input[name="district"]').val(),
			city: $form.find('input[name="city"]').val(),
			address: $form.find('input[name="address"]').val()
		}, function(response) {
			$btn.prop('disabled', false).text('Сохранить изменения');
			if (response.success) {
				$entity.find('.legal-entity__name').text(response.data.fields.org_name);
				$form.slideUp();
			} else {
				alert(response.data || 'Ошибка');
			}
		}).fail(function() {
			$btn.prop('disabled', false).text('Сохранить изменения');
		});
	});

	// ============ Legal Entities: Delete ============
	var deleteEntityId = null;

	$(document).on('click', '.legal-entity__btn-delete', function() {
		deleteEntityId = $(this).closest('.legal-entity').data('id');
		$('#popup-delete-confirm').addClass('active');
	});

	$('#btn-confirm-delete').on('click', function() {
		if (!deleteEntityId) return;
		var $btn = $(this);
		$btn.prop('disabled', true).text('...');

		$.post(titan_wc.ajax_url, {
			action: 'titan_legal_entity_delete',
			nonce: titan_wc.nonce,
			entity_id: deleteEntityId
		}, function(response) {
			$btn.prop('disabled', false).text('Подтверждаю');
			$('#popup-delete-confirm').removeClass('active');

			if (response.success) {
				$('.legal-entity[data-id="' + deleteEntityId + '"]').fadeOut(300, function() {
					$(this).remove();
				});
				deleteEntityId = null;
				$('#popup-delete-success').addClass('active');
			} else {
				alert(response.data || 'Ошибка');
			}
		}).fail(function() {
			$btn.prop('disabled', false).text('Подтверждаю');
		});
	});

	// ============ Legal Entities: Add New ============
	$('#btn-add-legal-entity').on('click', function() {
		$('#add-legal-entity-form').slideDown();
		$(this).hide();
	});

	$('.legal-entity__btn-cancel-add').on('click', function() {
		$('#add-legal-entity-form').slideUp(function() {
			$('#btn-add-legal-entity').show();
		});
	});

	// Enable/disable add button based on inputs
	$(document).on('input', '#add-legal-entity-form input', function() {
		var $form = $('#add-legal-entity-form');
		var orgName = $form.find('input[name="org_name"]').val();
		var $addBtn = $form.find('.legal-entity__btn-add');
		if (orgName && orgName.trim().length > 0) {
			$addBtn.prop('disabled', false).removeClass('btn-disabled');
		} else {
			$addBtn.prop('disabled', true).addClass('btn-disabled');
		}
	});

	$(document).on('click', '.legal-entity__btn-add', function() {
		var $form = $('#add-legal-entity-form');
		var $btn = $(this);

		$btn.prop('disabled', true).text('...');

		$.post(titan_wc.ajax_url, {
			action: 'titan_legal_entity_save',
			nonce: titan_wc.nonce,
			entity_id: 0,
			org_name: $form.find('input[name="org_name"]').val(),
			inn: $form.find('input[name="inn"]').val(),
			kpp: $form.find('input[name="kpp"]').val(),
			legal_address: $form.find('input[name="legal_address"]').val(),
			postal_code: $form.find('input[name="postal_code"]').val(),
			region: $form.find('input[name="region"]').val(),
			district: $form.find('input[name="district"]').val(),
			city: $form.find('input[name="city"]').val(),
			address: $form.find('input[name="address"]').val()
		}, function(response) {
			$btn.prop('disabled', true).addClass('btn-disabled').text('Добавить');
			if (response.success) {
				// Reload page to show new entity
				location.reload();
			} else {
				alert(response.data || 'Ошибка');
			}
		}).fail(function() {
			$btn.prop('disabled', false).text('Добавить');
		});
	});

	// ============ Checkout: Buyer Type Tabs ============
	$(document).on('click', '.checkout-buyer-tab', function() {
		var buyer = $(this).data('buyer');
		$('.checkout-buyer-tab').removeClass('active');
		$(this).addClass('active');
		$('.checkout-buyer-panel').hide();
		$('.checkout-buyer-panel[data-buyer-panel="' + buyer + '"]').show();
	});

	// ============ Checkout: Delivery Tabs ============
	$(document).on('click', '.checkout-delivery-tab', function() {
		var delivery = $(this).data('delivery');
		$('.checkout-delivery-tab').removeClass('active');
		$(this).addClass('active');
		$('.checkout-delivery-panel').hide();
		$('.checkout-delivery-panel[data-delivery-panel="' + delivery + '"]').show();
	});

	// ============ Checkout: Total Toggle ============
	$(document).on('click', '.checkout-total__toggle', function() {
		$(this).toggleClass('open');
		$(this).closest('.checkout-total').find('.checkout-total__details').slideToggle();
	});

	// ============ Checkout: Quantity Update ============
	$(document).on('click', '.checkout-table .quantity-block .minus, .checkout-table .quantity-block .plus', function() {
		var $row = $(this).closest('.checkout-table__row');
		var key = $row.data('cart-item-key');
		var $input = $row.find('.number');
		var qty = parseInt($input.val()) || 1;

		if ($(this).hasClass('minus')) {
			qty = Math.max(1, qty - 1);
		} else {
			qty = qty + 1;
		}
		$input.val(qty);

		$.post(titan_wc.ajax_url, {
			action: 'titan_update_checkout_qty',
			nonce: titan_wc.nonce,
			cart_item_key: key,
			quantity: qty
		}, function(response) {
			if (response.success) {
				var d = response.data;
				// Update item subtotal
				$.each(d.items, function(i, item) {
					if (item.key === key) {
						$row.find('.total').html(item.subtotal + ' ₽');
					}
				});
				$('.checkout-subtotal__val').html(d.subtotal);
				$('.checkout-total__val').html(d.total);
				updateCartBadge(d.cart_count);
			}
		});
	});

	// ============ Checkout: Legal Entity Select ============
	$(document).on('change', 'select[name="legal_entity"]', function() {
		var $selected = $(this).find(':selected');
		var inn = $selected.data('inn') || '';
		var kpp = $selected.data('kpp') || '';
		var $panel = $(this).closest('.checkout-buyer-panel');
		$panel.find('input[name="inn"]').val(inn);
		$panel.find('input[name="kpp"]').val(kpp);
	});

	// ============ Checkout: File Attachment ============
	$(document).on('change', '.checkout-attach-btn input[type="file"]', function() {
		var file = this.files[0];
		if (file) {
			var $attachments = $(this).closest('.checkout-attachments');
			$attachments.find('.checkout-attachment__name').text(file.name);
			$attachments.find('.checkout-attachment').show();
			$attachments.find('.checkout-attach-btn').hide();
		}
	});

	$(document).on('click', '.checkout-attachment__delete', function() {
		var $attachments = $(this).closest('.checkout-attachments');
		$attachments.find('.checkout-attachment').hide();
		$attachments.find('.checkout-attach-btn').show();
		$attachments.find('input[type="file"]').val('');
	});

	// ============ Checkout: Submit Order ============
	$(document).on('click', '.checkout-submit', function() {
		var $btn = $(this);
		var buyerType = $btn.data('buyer-type');
		var $panel = $btn.closest('.checkout-buyer-panel');

		// Validate checkboxes
		var allChecked = true;
		$panel.find('.checkbox input[type="checkbox"]').each(function() {
			if (!$(this).is(':checked')) {
				allChecked = false;
				$(this).closest('.checkbox').find('.check').addClass('error');
			} else {
				$(this).closest('.checkbox').find('.check').removeClass('error');
			}
		});
		if (!allChecked) return;

		$btn.prop('disabled', true).text('...');

		var formData = new FormData();
		formData.append('action', 'titan_place_order');
		formData.append('nonce', titan_wc.nonce);
		formData.append('buyer_type', buyerType);
		formData.append('last_name', $panel.find('input[name="lastname"]').val());
		formData.append('first_name', $panel.find('input[name="firstname"]').val());
		formData.append('surname', $panel.find('input[name="patronymic"]').val());
		formData.append('email', $panel.find('input[name="email"]').val());
		formData.append('phone', $panel.find('input[name="phone"]').val());

		if (buyerType === 'physical') {
			var deliveryMethod = $('.checkout-delivery-tab.active').data('delivery') || 'delivery';
			formData.append('delivery_method', deliveryMethod);
			formData.append('recipient', $panel.find('input[name="recipient"]').val());
			formData.append('comment', $panel.find('textarea[name="comment"]').val());

			// CDEK data
			if (deliveryMethod === 'delivery') {
				formData.append('cdek_office_code', $('.cdek-office-code').val());
				formData.append('cdek_city_code', $('input[name="cdek_city_code"]').val());
				formData.append('cdek_delivery_cost', $('input[name="cdek_delivery_cost"]').val());
				formData.append('cdek_tariff_code', $('input[name="cdek_tariff_code"]').val());
				formData.append('cdek_office_address', $('.cdek-office-info').text() || '');
			}
		} else {
			formData.append('legal_entity_id', $panel.find('select[name="legal_entity"]').val() || '');
			formData.append('inn', $panel.find('input[name="inn"]').val());
			formData.append('kpp', $panel.find('input[name="kpp"]').val());
			formData.append('comment', $panel.find('textarea[name="comment"]').val());

			// File upload
			var fileInput = $panel.find('input[name="requisites"]')[0];
			if (fileInput && fileInput.files[0]) {
				formData.append('requisites', fileInput.files[0]);
			}
		}

		$.ajax({
			url: titan_wc.ajax_url,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				$btn.prop('disabled', false).text(buyerType === 'legal' ? 'Выставить счёт' : 'Заказать');
				if (response.success) {
					updateCartBadge(0);
					if (response.data.payment_url) {
						window.location.href = response.data.payment_url;
					} else {
						$('#popup-checkout-success').addClass('active');
					}
				} else {
					alert(response.data || 'Ошибка при оформлении заказа');
				}
			},
			error: function() {
				$btn.prop('disabled', false).text(buyerType === 'legal' ? 'Выставить счёт' : 'Заказать');
				alert('Ошибка сервера');
			}
		});
	});

	// ============ Checkout Success: Back to Account ============
	$('#btn-back-to-account').on('click', function() {
		$('#popup-checkout-success').removeClass('active');
		window.location.href = titan_wc.account_url;
	});

	// ============ History: Toggle Details ============
	$(document).on('click', '.history-details-toggle', function(e) {
		e.preventDefault();
		var $row = $(this).closest('.history-table__row');
		var $detail = $row.next('.history-table__detail');
		$detail.slideToggle();
	});

	$(document).on('click', '.history-detail-close', function(e) {
		e.preventDefault();
		$(this).closest('.history-table__detail').slideUp();
	});

	// ============ Popup: Close ============
	$(document).on('click', '.popupblock .close', function() {
		$(this).closest('.popupblock').removeClass('active');
	});

	$(document).on('click', '.popupblock', function(e) {
		if (e.target === this) {
			$(this).removeClass('active');
		}
	});

	// ============ CDEK Integration ============
	var cdekCityCode = null;
	var cdekTimer;

	// City input: fetch offices, update the plugin's .open-pvz-btn
	$(document).on('input', '.cdek-city-input', function() {
		var city = $(this).val().trim();
		clearTimeout(cdekTimer);

		if (city.length < 2) {
			$('.open-pvz-btn').hide();
			$('.cdek-office-info').remove();
			$('.cdek-office-code').val('');
			cdekCityCode = null;
			resetCdekCost();
			return;
		}

		cdekTimer = setTimeout(function() {
			$.post(titan_wc.ajax_url, {
				action: 'titan_cdek_offices',
				nonce: titan_wc.nonce,
				city: city
			}, function(response) {
				if (response.success) {
					cdekCityCode = response.data.city_code;
					$('input[name="cdek_city_code"]').val(cdekCityCode);

					// Fill the plugin's expected HTML structure
					var $pvzBtn = $('.open-pvz-btn');
					$pvzBtn.attr('data-city', city);
					$pvzBtn.find('script').text(response.data.offices);
					$pvzBtn.find('a').text('Выбрать пункт выдачи');
					$pvzBtn.show();

					// Clear previous selection
					$('.cdek-office-info').remove();
					$('.cdek-office-code').val('');
					resetCdekCost();
				} else {
					$('.open-pvz-btn').hide();
					cdekCityCode = null;
					resetCdekCost();
				}
			});
		}, 600);
	});

	// Listen for update_checkout — the plugin triggers it after office selection
	$(document.body).on('update_checkout', function() {
		var officeCode = $('.cdek-office-code').val();
		if (officeCode && cdekCityCode) {
			calculateCdekCost();
		}
	});

	function resetCdekCost() {
		$('.cdek-delivery-cost').hide();
		$('input[name="cdek_delivery_cost"]').val('');
		$('input[name="cdek_tariff_code"]').val('');
		$('.checkout-total__details .checkout-total__row').last().find('span').last().html('—');
	}

	function calculateCdekCost() {
		if (!cdekCityCode) return;

		$.post(titan_wc.ajax_url, {
			action: 'titan_cdek_calculate',
			nonce: titan_wc.nonce,
			city_code: cdekCityCode
		}, function(response) {
			if (response.success) {
				var d = response.data;
				$('input[name="cdek_delivery_cost"]').val(d.cost);
				$('input[name="cdek_tariff_code"]').val(d.tariff_code);

				$('.cdek-delivery-cost__val').html(d.cost_format);
				$('.cdek-delivery-cost__days').text('(' + d.days + ' дн.)');
				$('.cdek-delivery-cost').show();

				$('.checkout-total__details .checkout-total__row').last().find('span').last().html(d.cost_format);

				var cartTotal = parseFloat($('.checkout-subtotal__val').text().replace(/[^\d.,]/g, '').replace(',', '.')) || 0;
				var newTotal = cartTotal + parseFloat(d.cost);
				$('.checkout-total__val').html(newTotal.toLocaleString('ru-RU') + ' ₽');
			}
		});
	}
});
</script>
