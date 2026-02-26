$(document).ready(function(){
	$('a.popup').on('click', function(){
		var href = $(this).attr('href');
		$('.popupblock'+href).fadeIn('300');
		return false;
	});

	$('.burger').on('click', function(){
		$('.popupmenu').fadeIn(300);
		return false;
	});

	$('.popupmenu .close').on('click', function(){
		$(this).parents('.popupmenu').fadeOut("300");
		return false;
  });

	$('.popupblock .close').on('click', function(){
		$(this).parents('.popupblock').fadeOut("300");
		return false;
  });

	$('.cookies .close').on('click', function(){
		$(this).parents('.cookies').fadeOut("300");
		return false;
  });

	$('.cookies .ok-btn').on('click', function(){
		$(this).parents('.cookies').fadeOut("300");
		return false;
  });

	$(document).on('keyup', function(e) {
    if ( e.key == "Escape" ) {
      $('.popupblock').fadeOut("300");
    }
  });

	$('input[type="name"]').on('input', function(){
	 if (this.value.match(/[^а-яА-Яa-zA-Z]/s)) {
		this.value = this.value.replace(/[^а-яА-Яa-zA-Z]/s, '');
	}
	});

	var selector = $('input[type="tel"]');

	var im = new Inputmask("+7 (999) 999 99-99");
	im.mask(selector);

	$('label.file input[type="file"]').on('change', function(e){
		var fileName = e.target.files[0].name;
		$(this).parents('.form-inner').find('.file-item').show().find('.name').html(fileName);
	});

	$('.file-item .delete').on('click', function(){
		$(this).parents('.file-item').hide().find('.name').html('');
		$(this).parents('.form-inner').find('.file').find('input[type="file"]').val('');
	})
	
	$('.select .current').on('click', function(){
		if($(this).hasClass('open')){
			$(this).removeClass('open').parents('.select').find('.list').fadeOut(300);
		} else{
			$(this).addClass('open').parents('.select').find('.list').fadeIn(300);
		}
	});

	$('.select .list .option').on('click', function(){
		if($(this).not('active')){
			var name = $(this).html();
			$(this).parents('.list').find('.option').removeClass('active')
			$(this).addClass('active').parents('.select').find('.current').removeClass('open').addClass('selected').html(name);
			$(this).parents('.list').fadeOut(300);
			$(this).parents('.select-field').find('.caption').show(300);
		}
		return false;
	});

	$(document).mouseup(function (e) {
		var div = $(".select .list");
		var btn = $(".select .current");
		if (!div.is(e.target) && div.has(e.target).length === 0 && !btn.is(e.target) && btn.has(e.target).length === 0) {
			div.fadeOut(300);
			btn.removeClass('open')
		}
	});

	$(document).mouseup(function (e) {
		var div = $(".header__search");
		if (!div.is(e.target) && div.has(e.target).length === 0) {
			$('.header__search').removeClass('active').find('.search-result').hide(300);
		}
	});

	$('.calc-form .form-field input').on('input', function(){
		$(this).parents('.form-field').find('.caption').show(300);
	});

	$('.tabs .tab-title').on('click', function(){
		if($(this).not('.active')){
			var id = $(this).attr('href');
			$(this).parents('.tabs-title').find('.tab-title').removeClass('active');
			$(this).addClass('active');
			$(this).parents('.tabs').find('.tab').hide();
			$(this).parents('.tabs').find('.tab'+id).show();
		}
		return false;
	});

	$('.calc-form .select .option').on('click', function(){
		calculator();
	});

	$('.calc-form .form-field input').on('change', function(){
		calculator();
	})

	function calculator(){
		var stage1 = 0,
				stage2 = 0,
				stage3 = 0,
				dataIn = $('.calc-form .select .trafaret.active').attr('data-valIn'),
				dataOut = $('.calc-form .select .trafaret.active').attr('data-valOut'),
				valIndot = $('.calc-form .indot').val(),
				valOutdot = $('.calc-form .outdot').val(),
				percent = $('.calc-form .select .components.active').attr('data-percent'),
				overprice = $('.calc-form .select .components.active').attr('data-overprice'),
				wash = $('.calc-form .select .components.active').attr('data-wash'),
				mod = $('.calc-form .select .quantity.active').attr('data-val');

		if(valIndot != ''){
			var price1 = parseFloat(valIndot) * parseFloat(dataIn);
		}else{
			var price1 = 0;
		}
		if(valOutdot != ''){
			var price2 = parseFloat(valOutdot) * parseFloat(dataOut);
		}else{
			var price2 = 0;
		}

		if($('.calc-form .select .trafaret').hasClass('active')){
			var stage1 = price1 + price2;
		} else{
			var stage1 = 0;
		}

		console.log('этап 1 ' + stage1);

		if($('.calc-form .select .component1').hasClass('active')){
			var r1 = parseFloat(stage1) * parseFloat(overprice);
			var r2 = parseFloat(r1) + parseFloat(wash);
			var resultPercent = r2/100 * percent
			stage2 = parseFloat(r2) + resultPercent;
		}
		if($('.calc-form .select .component2').hasClass('active')){
			var r1 = parseFloat(stage1) + parseFloat(wash);
			var r2 = parseFloat(r1)/100 * percent;
			var stage2 = parseFloat(r1) + parseFloat(r2)
		}

		console.log('этап 2 ' + stage2);

		var sum = stage2 * mod;
		stage3 = parseInt(sum);

		if(stage1 != 0 && stage2 != 0 && stage3 != 0){
			$('.calc-form .result-block .val span').html(stage3);
		} else{
			$('.calc-form .result-block .val span').html('0');
		}

		console.log('этап 3 ' + stage3);
	}

	$('.quantity-block .minus').on('click', function(){
		var num = $(this).parents('.quantity-block').find('.number').val();
		if(num > 1){
			num = parseInt(num) - parseInt(1);
			$(this).parents('.quantity-block').find('.number').val(num);
		}
	})

	$('.quantity-block .plus').on('click', function(){
		var num = $(this).parents('.quantity-block').find('.number').val();
		num = parseInt(num) + parseInt(1);
		$(this).parents('.quantity-block').find('.number').val(num);
	})

	$('input[type="search"]').on('input', function(){
		var text = $(this).val();
		var count = text.length;
		if(count > 3){
			$(this).parents('.search').find('.clean').fadeIn(300);
		} else{
			$(this).parents('.search').find('.clean').fadeOut(300);
		}
	});

	$('.search .clean').on('click', function(){
		$(this).fadeOut(300).parents('.search').find('input[type="search"]').val('');
	}); 

	// =========================================
	// Личный кабинет — Профиль
	// =========================================

	// Sidebar tabs — переключение панелей
	$('.account-sidebar__item[data-tab]').on('click', function(e){
		e.preventDefault();
		var tab = $(this).attr('data-tab');
		$('.account-sidebar__item').removeClass('active');
		$(this).addClass('active');
		$('.account-panel').hide();
		$('.account-panel[data-panel="' + tab + '"]').show();
	});

	// Изменить пароль
	$('#btn-change-password').on('click', function(){
		$('#profile-view-actions').hide();
		$('#edit-profile-form').hide();
		$('#password-form').slideDown(300);
	});

	// Сохранить пароль
	$('#btn-save-password').on('click', function(){
		$('#password-form').slideUp(300);
		$('#profile-view-actions').show();
		$('#password-form').find('input').val('');
		$('#popup-password-success').fadeIn(300);
	});

	// Редактировать профиль
	$('#btn-edit-profile').on('click', function(){
		$('#profile-view-actions').hide();
		$('#password-form').hide();
		$('#edit-profile-form').slideDown(300);
	});

	// Не сохранять редактирование профиля
	$('#btn-cancel-edit').on('click', function(){
		$('#edit-profile-form').slideUp(300);
		$('#profile-view-actions').show();
	});

	// Сохранить редактирование профиля
	$('#btn-save-profile').on('click', function(){
		$('#edit-profile-form').slideUp(300);
		$('#profile-view-actions').show();
	});

	// Редактировать юр. лицо
	$(document).on('click', '.legal-entity__btn-edit', function(){
		var entity = $(this).closest('.legal-entity');
		entity.addClass('editing');
		entity.find('.legal-entity__edit-form').slideDown(300);
	});

	// Не сохранять редактирование юр. лица
	$(document).on('click', '.legal-entity__btn-cancel', function(){
		var entity = $(this).closest('.legal-entity');
		entity.removeClass('editing');
		entity.find('.legal-entity__edit-form').slideUp(300);
	});

	// Сохранить редактирование юр. лица
	$(document).on('click', '.legal-entity__btn-save', function(){
		var entity = $(this).closest('.legal-entity');
		entity.removeClass('editing');
		entity.find('.legal-entity__edit-form').slideUp(300);
	});

	// Удалить юр. лицо — показать попап подтверждения
	var entityToDelete = null;
	$(document).on('click', '.legal-entity__btn-delete', function(){
		entityToDelete = $(this).closest('.legal-entity');
		$('#popup-delete-confirm').fadeIn(300);
	});

	// Подтвердить удаление
	$('#btn-confirm-delete').on('click', function(){
		$('#popup-delete-confirm').fadeOut(300);
		if(entityToDelete){
			entityToDelete.slideUp(300, function(){
				$(this).remove();
			});
			entityToDelete = null;
		}
		setTimeout(function(){
			$('#popup-delete-success').fadeIn(300);
		}, 400);
	});

	// Добавить юр. лицо — показать форму
	$('#btn-add-legal-entity').on('click', function(){
		$('#add-legal-entity-form').slideDown(300);
	});

	// Отмена добавления юр. лица
	$(document).on('click', '.legal-entity__btn-cancel-add', function(){
		$('#add-legal-entity-form').slideUp(300);
		$('#add-legal-entity-form').find('input[type="text"]').val('');
	});

	// Валидация формы добавления — активация кнопки «Добавить»
	$(document).on('input change', '#add-legal-entity-form input', function(){
		var form = $('#add-legal-entity-form');
		var allFilled = true;
		form.find('.legal-entity__fields input').each(function(){
			if($(this).val().trim() === ''){
				allFilled = false;
			}
		});
		var allChecked = true;
		form.find('input[type="checkbox"]').each(function(){
			if(!$(this).is(':checked')){
				allChecked = false;
			}
		});
		var addBtn = form.find('.legal-entity__btn-add');
		if(allFilled && allChecked){
			addBtn.prop('disabled', false).removeClass('btn-disabled');
		} else {
			addBtn.prop('disabled', true).addClass('btn-disabled');
		}
	});

	// Добавить юр. лицо — сохранить
	$(document).on('click', '.legal-entity__btn-add', function(){
		if($(this).prop('disabled')) return;
		var form = $('#add-legal-entity-form');
		var name = form.find('input[name="org_name"]').val();

		var newEntity = '<div class="legal-entity">' +
			'<div class="legal-entity__header">' +
				'<div class="legal-entity__name">' + name + '</div>' +
				'<div class="legal-entity__controls">' +
					'<button class="legal-entity__btn-edit" title="Редактировать">' +
						'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.7279 9.57629L14.3137 8.16207L5 17.4758V18.8901H6.41421L15.7279 9.57629ZM17.1421 8.16207L18.5563 6.74786L17.1421 5.33365L15.7279 6.74786L17.1421 8.16207ZM7.24264 20.8901H3V16.6474L16.435 3.21233C16.8256 2.8218 17.4587 2.8218 17.8492 3.21233L20.6777 6.04075C21.0682 6.43128 21.0682 7.06444 20.6777 7.45497L7.24264 20.8901Z" fill="black"/></svg>' +
					'</button>' +
					'<button class="legal-entity__btn-delete" title="Удалить">' +
						'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 5C20 5.55228 19.5523 6 19 6H18.997L18.064 19.142C18.0281 19.6466 17.8023 20.1188 17.4321 20.4636C17.0619 20.8083 16.5749 20.9999 16.069 21H7.93C7.42414 20.9999 6.93707 20.8083 6.56688 20.4636C6.19669 20.1188 5.97093 19.6466 5.935 19.142L5.003 6H5C4.44772 6 4 5.55228 4 5C4 4.44772 4.44772 4 5 4H9C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4H19C19.5523 4 20 4.44772 20 5ZM7.003 6L7.931 19H16.069L16.997 6H7.003Z" fill="black"/></svg>' +
					'</button>' +
				'</div>' +
			'</div>' +
		'</div>';

		$('#legal-entities-list').append(newEntity);
		form.slideUp(300);
		form.find('input[type="text"]').val('');
		form.find('input[type="checkbox"]').prop('checked', false);
		form.find('.legal-entity__btn-add').prop('disabled', true).addClass('btn-disabled');
	});

	// Видимость пароля (для страницы ЛК)
	$('.profile-card__password-form .password .visible__text').on('click', function(){
		var input = $(this).siblings('input');
		if(input.attr('type') === 'password'){
			input.attr('type', 'text');
		} else {
			input.attr('type', 'password');
		}
		$(this).toggleClass('active');
	});

	// =========================================
	// Личный кабинет — Оформление заказа
	// =========================================

	// Табы покупателя: Физ./Юр. лицо
	$('.checkout-buyer-tab').on('click', function(){
		var buyer = $(this).attr('data-buyer');
		$('.checkout-buyer-tab').removeClass('active');
		$(this).addClass('active');
		$('.checkout-buyer-panel').hide();
		$('.checkout-buyer-panel[data-buyer-panel="' + buyer + '"]').show();
	});

	// Табы доставки: Доставка/Самовывоз
	$('.checkout-delivery-tab').on('click', function(){
		var delivery = $(this).attr('data-delivery');
		$('.checkout-delivery-tab').removeClass('active');
		$(this).addClass('active');
		$('.checkout-delivery-panel').hide();
		$('.checkout-delivery-panel[data-delivery-panel="' + delivery + '"]').show();
	});

	// Тоггл раскрытия итоговой суммы
	$('.checkout-total__header').on('click', function(){
		var details = $(this).siblings('.checkout-total__details');
		var toggle = $(this).find('.checkout-total__toggle');
		details.slideToggle(300);
		toggle.toggleClass('open');
	});

	// Прикрепить реквизиты
	$('.checkout-attach-btn input[type="file"]').on('change', function(e){
		if(e.target.files.length > 0){
			var fileName = e.target.files[0].name;
			var attachment = $(this).closest('.checkout-attachments').find('.checkout-attachment');
			attachment.find('.checkout-attachment__name').text(fileName);
			attachment.show();
		}
	});

	// Удалить прикрепленный файл
	$(document).on('click', '.checkout-attachment__delete', function(){
		var attachments = $(this).closest('.checkout-attachments');
		$(this).closest('.checkout-attachment').hide().find('.checkout-attachment__name').text('');
		attachments.find('input[type="file"]').val('');
	});

	// Отправить заказ
	$('.checkout-submit').on('click', function(){
		$('#popup-checkout-success').fadeIn(300);
	});

	// Вернуться в ЛК из попапа успеха
	$('#btn-back-to-account').on('click', function(){
		$('#popup-checkout-success').fadeOut(300);
		$('.account-sidebar__item[data-tab="profile"]').trigger('click');
	});

	// =========================================
	// Личный кабинет — История заказов
	// =========================================

	// Подробнее — раскрыть детализацию
	$(document).on('click', '.history-details-toggle', function(e){
		e.preventDefault();
		var row = $(this).closest('.history-table__row');
		var detail = row.next('.history-table__detail');
		if(detail.is(':visible')){
			detail.slideUp(300);
		} else {
			detail.slideDown(300);
		}
	});

	// Закрыть детализацию
	$(document).on('click', '.history-detail-close', function(e){
		e.preventDefault();
		$(this).closest('.history-table__detail').slideUp(300);
	});

});