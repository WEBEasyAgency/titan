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

});