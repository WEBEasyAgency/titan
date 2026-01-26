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
			$('.select .list .option').removeClass('active');
			$(this).addClass('active').parents('.select').find('.current').removeClass('open').html(name);
			$(this).parents('.list').fadeOut(300);
		}
	});

	$(document).mouseup(function (e) {
		var div = $(".header__search");
		if (!div.is(e.target) && div.has(e.target).length === 0) {
			$('.header__search').removeClass('active').find('.search-result').hide(300);
		}
	});
});