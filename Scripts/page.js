$('img').ready(function() {
	$('html').css({'display':'block'});	
});
$(window).resize(function(event){
	function bHeight(){//для футера
		$('body').css({
			'height': $('header').height()+$('main').height()+$('footer').height()
		});
	};
	bHeight();
});
window.addEventListener("orientationchange", function() {
	function bHeight(){//для футера
	$('body').css({
		'height': $('header').height()+$('main').height()+$('footer').height()
	});
	};
	bHeight();
}, false);
$('.action_wrapper_form_tel').ready(function() {
	$('.action_wrapper_form_tel').attr({'pattern':'[0-9]{10}','required':'true'});
	$('.action_wrapper_form_name').attr('required','true');
});