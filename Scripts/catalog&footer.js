var post_per_page;
if($(window).width()<=767){
	post_per_page=10;
}
if($(window).width()<=480){
	post_per_page=6;
}
if($(window).width()>767){
	post_per_page=14;
}
//выплывающий футер
var $footer;
jQuery('img').ready(function($) {
	$footer=$('header').height()+$('main').height()+$('footer').height();
	function bHeight(){
		$('body').css({
			'height': $footer
		});
		$('footer').css('position', 'fixed');
	};
	bHeight();
	});
//модальные окна
	function openModal() {
		$('.modal').attr('id','modal');
	};
	function closeModal(){
		$('.modal').attr('id',null);
	};
	function openPW(){
		$('.modal_pw').attr('id',null);
	}
	function closePW(){
		$('.modal_pw').attr('id','pw')
	}
	function openAll() {
		closePW()
		$('#all-char').addClass('modal_all').attr('id',null);	
	};
	function closeAll() {
		$('.modal_all').attr('id','all-char').removeClass('modal_all')
		openPW();

	};
	function openReviews() {
		$('#reviews').addClass('modal_reviews');
		$('.modal_reviews').attr('id',null);
	};
	function closeReviews() {
		$('.modal_reviews').attr('id','reviews');
		$('#reviews').removeClass('modal_reviews');
	};
	function openForms(){
		closeReviews();
		$('#forms').addClass('modal_forms');
		$('.modal_forms').attr('id',null);
	};
	function closeForms(){
		$('.modal_forms').attr('id','forms');
		$('#forms').removeClass('modal_forms');
		openReviews()
	};
	//Стрелка суб катигории
	function sub_cats_arrow(c){
		if($('#sub_cats_arrow'+c).attr('data-state')=='0'){
			$('#sub_cats_arrow'+c).css('transform', 'rotate(180deg)');
			$('#sub_cats_wrapper'+c).css({'display':'inline','height':'fit-content'});
			$('#sub_cats_arrow'+c).attr('data-state','1');
			return true;
		}
		if($('#sub_cats_arrow'+c).attr('data-state')=='1'){
			$('#sub_cats_arrow'+c).css('transform', 'rotate(0deg)');
			$('#sub_cats_wrapper'+c).css({'display':'none','height':'0'});
			$('#sub_cats_arrow'+c).attr('data-state','0');
			return true;
		}
	}
    //кнопка весь котолог открытие 
    function allCotolog(){
    	$('main').css('display', 'none');
    	$('footer').css('display', 'none');
    	$('nav').attr('id', 'onscroll').removeClass('nav');
    	$('.menu_basket').siblings().css('display', 'none');
    	$('.welcome_inscription').css('display', 'none');
    	$('body').css({'height':'100vh','overflow':'hidden'});
    	$('header').css('height', '0');
    	$('#all').load(blog_link+'/Pages/catalog.php',function() {
    		$('#all').css({'display':'block'});
    		$('.all-catalog-style').append('@import url(\''+blog_link+'/Styles/catalog.css\');');
    		$('.all-catalog__rubricator_body').append('<div class="rubricator_lodaing">Загрузка...</div>');
    		$('.search_img').attr('src', blog_link+'/Imeges/Search_icon.png');
    		$('.product_sort').append('<img class=\'sort_arrow\' src=\''+blog_link+'/Imeges/small_white_arrow.png\'>');
    		$('.search_input').focus(function(){
    			$('.search').css({ 'box-shadow': 'inset 0px 0px 2.5px 2px rgba(0, 0, 0,0.5)'});
    		});
    		$('.search_input').focusout(function(){
    			$('.search').css({'box-shadow': 'inset 0px 0px 2px 1.5px rgba(0, 0, 0,0.5)'});
    		});
    		$('.memu_icon').css('bottom', '25%');
    		if(post_per_page<=6){
    			$('.small_screen_cats').css('display', 'flex');
    			$('.all-catalog__rubricator_x').ready(function(){
    				open_small_screen_cat();
    			});
    		}
    		ajaxes.push($.post(ajax.url,{nonce: nonce,action:'ac_ac'}, function(xhr) {
    			bild_cats(xhr);
				$('.count,.cats_text').ready(function(){
					all_cotalog_cats_pages();
					//Кнопка "Соритировать по"
					$('.product_sort').on('click', function(event) {
						if($('#product_sort_menu').css('display')=='none'){
							$('#product_sort_menu').css({'display':'inline'});
							$('.sort_arrow').css('transform', 'rotate(180deg)');
						}
						else{
							$('#product_sort_menu').css({'display':'none'});
							$('.sort_arrow').css('transform', 'rotate(0deg)');
						}

					});
				});
    		}));
    		load_all_products();
    		all_atolog_search();
    	});;
    };
   //кнопка весь котолог закрыте
    function homePage(){
    	if($('main').css('display')=='none'){
    		$('main').css('display', 'flex');
    		$('footer').css('display', 'flex');
    		$('nav').attr('id', null).addClass('nav');
    		$('.menu_basket').siblings().css({'display' : 'block','color':'white'});
	    	$('.welcome_inscription').css('display', 'flex');
	    	$('body').css({'overflow': 'auto'});
	    	$('#all').empty();
	    	$('header').css('height', '100vh');
	    	$('body').css({'height': $footer});
	    	$('#all').css({'display':'none'});
	    	$('#alls_screen_small').css('display','none');
	    	if(post_per_page<=480){
    			$('.small_screen_cats').css('display', 'none');
    		}
    	}
    	else{
    		return true;
    	}
    };
//Функция кнопки сброса поиска
	function reset_search(){
		$('.reset_search').on('click', function(event) {
			$(this).attr('id',null);
			$('.search')[0].reset();
			$('.products').empty();
			$('#sort_active').attr('id',null);
			$('.product_sort_bypop').attr('id','sort_active');
			$('.pages_count').empty();
			$('.cats_all#active').attr('id', null);
			$('.cats_text#active').attr('id', null);
			$('.cats_all').attr('id', 'active');
			$('.all-catalog__rubricator').empty();
			$('.all-catalog__rubricator').append('<div class="rubricator_lodaing">Загрузка...</div>');
			load_all_products();
			    		$.post(ajax.url,{nonce: nonce,action:'ac_ac'}, function(xhr) {
    			bild_cats(xhr);
				$('.count,.cats_text').ready(function(){
					all_cotalog_cats_pages();
		});
	});
})
}
$('.memu_icon').on('click', function(event) {
	if($('.menu_list').attr('id')=='opened'){
		$('.menu_list').attr('id', null);
		$('.memu_icon').attr('src', blog_link+'/Imeges/Mafon.kz Menu Icon.png');
		$('.memu_icon').css({'height':'15px','position':'static'});
		$('.bascket-modal').attr('id', null);
		$('.bascket_ordered_button').attr('id', null);
	}
	else{
		$('.menu_list').attr('id','opened');
		$('.memu_icon').attr('src', blog_link+'/Imeges/x-icon-white.png');
		$('.memu_icon').css({'height':'20px','position':'fixed'});
	}
});
function open_small_screen_cat(){
	$('.all-catalog__rubricator_x').attr('src', blog_link+'/Imeges/x-icon-white.png');
	$('.all-catalog__rubricator_x').on('click',function(event) {
		$('.all-catalog__rubricator').attr('id', null);
	});
	$('.small_screen_cats').on('click', function(event) {
		$('.all-catalog__rubricator').attr('id', 'opened');
	});
}