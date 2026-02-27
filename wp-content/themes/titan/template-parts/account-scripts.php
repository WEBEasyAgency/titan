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
		$('#titan_buyer_type').val(buyer);

		if (buyer === 'legal') {
			// Auto-select invoice gateway
			$('#payment_method_titan_invoice').prop('checked', true).trigger('change');
			// Hide shipping + extra fields for legal entities
			$('.checkout-shipping').hide();
			$('.checkout-extra-fields').hide();
			// Change submit button text
			$('#place_order').text('Выставить счёт');
		} else {
			// Auto-select T-Bank gateway
			$('#payment_method_tbank').prop('checked', true).trigger('change');
			// Show shipping + extra fields for physical persons
			$('.checkout-shipping').show();
			$('.checkout-extra-fields').show();
			// Change submit button text
			$('#place_order').text('Заказать');
		}
	});

	// ============ Checkout: Quantity Update ============
	// Note: app.js already handles +/- value change on .quantity-block .minus/.plus
	// We only need to sync with server and trigger WC checkout refresh
	var qtyUpdateTimer;
	$(document).on('click', '.checkout-table .quantity-block .sign', function() {
		var $row = $(this).closest('.checkout-table__row');
		var key = $row.data('cart-item-key');
		var $input = $row.find('.number');

		clearTimeout(qtyUpdateTimer);
		qtyUpdateTimer = setTimeout(function() {
			var qty = parseInt($input.val()) || 1;
			$.post(titan_wc.ajax_url, {
				action: 'titan_update_checkout_qty',
				nonce: titan_wc.nonce,
				cart_item_key: key,
				quantity: qty
			}, function(response) {
				if (response.success) {
					var d = response.data;
					$.each(d.items, function(i, item) {
						if (item.key === key) {
							$row.find('.total').html(item.subtotal + ' ₽');
						}
					});
					$('.checkout-subtotal__val').html(d.subtotal);
					updateCartBadge(d.cart_count);
					$(document.body).trigger('update_checkout');
				}
			});
		}, 300);
	});

	// ============ Checkout: Legal Entity Select ============
	$(document).on('change', 'select[name="titan_legal_entity"]', function() {
		var $selected = $(this).find(':selected');
		var inn = $selected.data('inn') || '';
		var kpp = $selected.data('kpp') || '';
		var $panel = $(this).closest('.checkout-buyer-panel');
		$panel.find('input[name="titan_inn"]').val(inn);
		$panel.find('input[name="titan_kpp"]').val(kpp);
	});

	// ============ Checkout: File Attachment (AJAX upload) ============
	$(document).on('change', '.checkout-attach-btn input[type="file"]', function() {
		var file = this.files[0];
		if (!file) return;

		var $attachments = $(this).closest('.checkout-attachments');
		$attachments.find('.checkout-attachment__name').text(file.name + ' (загрузка...)');
		$attachments.find('.checkout-attachment').show();
		$attachments.find('.checkout-attach-btn').hide();

		var formData = new FormData();
		formData.append('action', 'titan_upload_requisites');
		formData.append('nonce', titan_wc.nonce);
		formData.append('file', file);

		$.ajax({
			url: titan_wc.ajax_url,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				if (response.success) {
					$attachments.find('input[name="titan_requisites_id"]').val(response.data.attachment_id);
					$attachments.find('.checkout-attachment__name').text(file.name);
				} else {
					alert(response.data || 'Ошибка загрузки файла');
					$attachments.find('.checkout-attachment').hide();
					$attachments.find('.checkout-attach-btn').show();
				}
			},
			error: function() {
				alert('Ошибка сервера при загрузке файла');
				$attachments.find('.checkout-attachment').hide();
				$attachments.find('.checkout-attach-btn').show();
			}
		});
	});

	$(document).on('click', '.checkout-attachment__delete', function() {
		var $attachments = $(this).closest('.checkout-attachments');
		$attachments.find('.checkout-attachment').hide();
		$attachments.find('.checkout-attach-btn').show();
		$attachments.find('input[type="file"]').val('');
		$attachments.find('input[name="titan_requisites_id"]').val('');
	});

	// ============ Checkout: Initial gateway setup ============
	// On page load, auto-select T-Bank for physical persons (default)
	$(document).ready(function() {
		$('#payment_method_tbank').prop('checked', true).trigger('change');
	});

	// ============ Checkout: Show address field for courier delivery ============
	function checkCourierDelivery() {
		var $checked = $('input[name="shipping_method[0]"]:checked');
		if (!$checked.length) {
			$('.woocommerce-billing-fields').removeClass('show-address');
			return;
		}
		var label = $('label[for="' + $checked.attr('id') + '"]').text().toLowerCase();
		var isCourier = label.indexOf('курьер') !== -1;
		$('.woocommerce-billing-fields').toggleClass('show-address', isCourier);
	}
	$(document).on('change', 'input[name="shipping_method[0]"]', checkCourierDelivery);
	$(document.body).on('updated_checkout', checkCourierDelivery);

	// ============ Checkout: Totals toggle ============
	$(document).on('click', '.checkout-total__header', function() {
		var $total = $(this).closest('.checkout-total');
		var $details = $total.find('.checkout-total__details');
		var $toggle = $(this).find('.checkout-total__toggle');
		$details.slideToggle(200);
		$toggle.toggleClass('open');
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

});
</script>
