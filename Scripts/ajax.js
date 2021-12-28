let ajaxes= new Array();
//Модальное окно продукта
	$src_big=$('.modal_pw_center_img-big>img').attr('src');
	$src_small=$('.modal_pw_center_img-small>img').attr('src');
	$data_id=0;
	function modal_general(){
	$('.pc_wrapper , .catalog_wrapper_product-cart_prise-off').off('click');
	$('.pc_wrapper , .catalog_wrapper_product-cart_prise-off').on('click',function(){
	openModal();
	$data_id=$(this).attr('data-id');
	$('.modal_pw_center_img-big>img').attr('src', $src_big);
	$('.modal_pw_center_img-small>img').attr('src', $src_small);
	$('.modal_pw_center_text_name').empty();
	$('.modal_pw_center_text_name').append('Загрузка...');
	$('.modal_pw_center_text_characters').empty();
	$('.table').empty();
	$('.table').append('Загрузка...');
	$('.modal_pw_end_prise_old').empty();
	$('.modal_pw_end_prise_new').empty();
	$('.modal_reviews_body').empty();
	$('.modal_reviews_body').append('Загрузка...');
	$('.modal_pw_end_prise_button').attr('data-id', $data_id);
	$('.modal_pw_center_img-small_galery').empty();
	$($('.modal_pw_center_img-small>img')[0]).attr('id','modal_pw_center_img-small_active');
	$.post(ajax.url, {nonce: nonce,action:'pw', nonce: nonce, id:$data_id}, function(xhr){
		$('.modal_pw_center_img-big>img').attr('src', xhr.product.big_image_src);
		$('.modal_pw_center_img-small>img').attr('src',  xhr.product.small_image_src);
		$('.modal_pw_center_text_name').empty();
		$('.modal_pw_center_text_name').append( xhr.product.name);
		$('.modal_pw_center_text_characters').empty();
		$('.modal_pw_center_text_characters').append( xhr.product.short_description);
		$('.table').empty();
		$('.table').append( xhr.product.description);
		$('.modal_pw_end_prise_old').empty();
		$('.modal_pw_end_prise_old').append(xhr.product.old_prise);
		$('.modal_pw_end_prise_new').empty();
		$('.modal_pw_end_prise_new').append( xhr.product.new_price	);
    	$('.modal_reviews_body').empty();
    	if(xhr.reviews==null){
    		$('.modal_reviews_body').append('<div>Нет отзывов</div>');
    	}
    	else{
    		$.each(xhr.reviews, function(index, val) {
			$('.modal_reviews_body').append('<div class="modal_reviews_body_wrapper"><div class="modal_reviews_body_wrapper_name">'+val['author']+'</div><div class="modal_reviews_body_wrapper_time" style="align-self: flex-start; ">'+val['date']+'</div><div class="modal_reviews_body_wrapper_text" style="border-top: solid 2px yellow; padding-top:10px; margin:10px;">'+val['content']+'</div></div>');	
		});	
    	}
    	if(xhr.product.gallery!=null){
    		$.each(xhr.product.gallery, function(index, val) {
    			$('.modal_pw_center_img-small_galery').append('<img src="'+val.small+'" data-id="'+index+'">');
    		});
    		pw_big_img_change(xhr);
    	}
		});
	});
		}
	modal_general();
//Добавить отзыв о продукте
$('#forms').submit(function(event) {
	event.preventDefault();
	$('.modal_reviews_body').empty();
	$('.modal_reviews_body').append('Загрузка...');
	closeForms();
	openReviews();
	var inputs={};
	$(this).find('input').each(function(index, el) {
		inputs[$(this).attr('name')]=$(el).val();
	});
	$.post(ajax.url, {nonce: nonce,action:'ar', id:$data_id, inputs}, function(xhr){
		$('.modal_reviews_body').empty();
		$.each(xhr.reviews, function(index, val) {
		$('.modal_reviews_body').append('<div class="modal_reviews_body_wrapper"><div class="modal_reviews_body_wrapper_name">'+val['author']+'</div><div class="modal_reviews_body_wrapper_time" style="align-self: flex-start; ">'+val['date']+'</div><div class="modal_reviews_body_wrapper_text" style="border-top: solid 2px yellow; padding-top:10px; margin:10px;">'+val['content']+'</div></div>');	
		});		
	});
	$(this)[0].reset();
});
//Функция отвечаюшая за работу катигории и счетчика страниц и сортировки по в 'Все котегории'
function all_cotalog_cats_pages() {
	$('.cats_all,.cats_text,.count,.product_sort_bypop,.product_sort_byprise_up,.product_sort_byprise_down').on('click', function(){
		$this=this;
		let cat,sort,offset;
		let s=$('.search_input').val();
		offset=$($this).attr('data-page')*post_per_page-post_per_page;
		if($this==$('.count#active')[0] || $this==$('.cats_all#active')[0] || $this==$('.cats_text#active')[0]){
			return true;
		}
		if($this==$('#sort_active')[0]){
			return true;
		}
		$.each(ajaxes, function(index, val) {
		 	 val.abort();
		}); 
		$('.products').empty();
		$('.products').append('<div class="body_loading">Загрузка...</div>');
		if($($this).attr('class')=='product_sort_bypop'){
			offset=$('.count#active').attr('data-page')*post_per_page-post_per_page;
			$('#sort_active').attr('id',null);
			$($this).attr('id','sort_active');
			cat=$('.cats_text#active').attr('data-id');
			sort=$('#sort_active').attr('data-sort');
			ajaxes.push($.post(ajax, {nonce: nonce,action:'ac_lp',post_per_page,offset,cat,sort,s}, function(xhr) {
				bild_all_catolog_products(xhr);
				$('.pc_wrapper').ready(function(){modal_general();basket();});
				$('.pages_count,.cats_text').ready(function(){all_cotalog_cats_pages();});
			}));
		}
		if($($this).attr('class')=='product_sort_byprise_up'){
			offset=$('.count#active').attr('data-page')*post_per_page-post_per_page;
			$('#sort_active').attr('id',null);
			$($this).attr('id','sort_active');
			cat=$('.cats_text#active').attr('data-id');
			sort=$('#sort_active').attr('data-sort');
			ajaxes.push($.post(ajax, {nonce: nonce,action:'ac_lp',post_per_page,offset,cat,sort,s}, function(xhr) {
				bild_all_catolog_products(xhr);
				$('.pc_wrapper').ready(function(){modal_general();basket();});
				$('.pages_count,.cats_text').ready(function(){all_cotalog_cats_pages();});
			}));
		}
		if($($this).attr('class')=='product_sort_byprise_down'){
			offset=$('.count#active').attr('data-page')*post_per_page-post_per_page;
			$('#sort_active').attr('id',null);
			$($this).attr('id','sort_active');
			cat=$('.cats_text#active').attr('data-id');
			sort=$('#sort_active').attr('data-sort');
		ajaxes.push($.post(ajax, {nonce: nonce,action:'ac_lp',post_per_page,offset,cat,sort,s}, function(xhr) {
				bild_all_catolog_products(xhr);
				$('.pc_wrapper').ready(function(){modal_general();basket();});
				$('.pages_count,.cats_text').ready(function(){all_cotalog_cats_pages();});
			}));
		}
		sort=$('#sort_active').attr('data-sort');
		if($($this).attr('class')=='count'){
			$('.count#active').attr('id', null);
			$($this).attr('id','active');
			cat=$('.cats_text#active').attr('data-id');
		ajaxes.push($.post(ajax, {nonce: nonce,action:'ac_lp',post_per_page,offset,cat,sort,s}, function(xhr) {
				bild_all_catolog_products(xhr);
				$('.pc_wrapper').ready(function(){modal_general();basket();});
				$('.pages_count,.cats_text').ready(function(){all_cotalog_cats_pages();});
			}));
		}
		if($($this).attr('class')=='cats_text'){
			$('.pages_count').empty();
			$('.cats_all#active').attr('id', null);
			$('.cats_text#active').attr('id', null);
			$($this).attr('id', 'active');
			cat=$('.cats_text#active').attr('data-id');
		ajaxes.push($.post(ajax, {nonce: nonce,action:'ac_lc',post_per_page,offset,cat,s}, function(xhr) {
				bild_all_catolog_products(xhr);
				bild_all_catolog_pages_count(xhr);
				$('.pc_wrapper').ready(function(){modal_general();basket();});
				$('.pages_count,.cats_text').ready(function(){all_cotalog_cats_pages();});
			}));
		}
		if($($this).attr('class')=='cats_all'){
			$('#sort_active').attr('id',null);
			$('.product_sort_bypop').attr('id','sort_active');
			$('.pages_count').empty();
			$('.cats_all#active').attr('id', null);
			$('.cats_text#active').attr('id', null);
			$($this).attr('id', 'active');
			load_all_products();
		}
	});
}
//Функция выгрузка всех продуктов на все катигрии
function load_all_products(){
	let s=$('.search_input').val();
	$('.products').append('<div class="body_loading">Загрузка...</div>');
ajaxes.push($.post(ajax, {nonce: nonce,action : 'ac_lap', post_per_page,s}, function(xhr) {
		bild_all_catolog_products(xhr);
		bild_all_catolog_pages_count(xhr);
		$('.pc_wrapper').ready(function(){modal_general();basket();});
		$('.count,.cats_text').ready(function(){all_cotalog_cats_pages();});
	}));
}
//Функция построения 'Весь котолог' 
function bild_all_catolog_products(xhr) {
	$('.products').empty();
	if(xhr==0){
		$('.products').append('<div class="body_loading">Ничего не найдено</div>');
		return true;
	}
		$.each(xhr.products, function(index, val) {
			if(val['prise_old']==""){
				$('.products').append('<div class="catalog_wrapper_product-cart" data-id="'+val.id+'"><div class="catalog_wrapper_product-cart_body"><div class="pc_wrapper" onclick="" data-id="'+val.id+'" ><div class="catalog_wrapper_product-cart_body_imeg" ><img src="'+val.img+'"><div class="catalog_wrapper_product-cart_body_name" style="position: absolute;bottom: 25%;">'+val.name+'</div></div><div class="catalog_wrapper_product-cart_body_property"><a href="javascript://0" class="catalog_wrapper_product-cart_body_property_more">Подробнее..</a><div class="catalog_wrapper_product-cart_body_property_prise"><div class="catalog_wrapper_product-cart_body_property_prise_old">'+val.prise_old+'</div><div class="catalog_wrapper_product-cart_body_property_prise_new">'+val.peise_new+'</div></div></div></div><button class="catalog_wrapper_product-cart_body_button" onclick="null"><img src="'+blog_link+'/Imeges/Basket_icon.png" alt=""></button></div></div>');
			}
			else{
				let sala_per=(val['prise_old']-val['peise_new'])/(val['prise_old']/100);	
				$('.products').append('<div class="catalog_wrapper_product-cart" data-id="'+val.id+'"><div class="catalog_wrapper_product-cart_prise-off" onclick="" data-id="'+val.id+'">'+sala_per.toFixed()+'%</div><div class="catalog_wrapper_product-cart_body"><div class="pc_wrapper" onclick="" data-id="'+val.id+'" ><div class="catalog_wrapper_product-cart_body_imeg" ><img src="'+val.img+'"><div class="catalog_wrapper_product-cart_body_name" style="position: absolute;bottom: 25%;">'+val.name+'</div></div><div class="catalog_wrapper_product-cart_body_property"><a href="javascript://0" class="catalog_wrapper_product-cart_body_property_more">Подробнее..</a><div class="catalog_wrapper_product-cart_body_property_prise"><div class="catalog_wrapper_product-cart_body_property_prise_old">'+val.prise_old+'</div><div class="catalog_wrapper_product-cart_body_property_prise_new">'+val.peise_new+'</div></div></div></div><button class="catalog_wrapper_product-cart_body_button" onclick="null"><img src="'+blog_link+'/Imeges/Basket_icon.png" alt=""></button></div></div>');
			}
		});
}
//Функция построения счетчика страниц
function bild_all_catolog_pages_count(xhr) {
	$('.pages_count').empty();
	let i=1;
	do{
		$('.pages_count').append('<div class="count" data-page="'+i+'">'+i+'</div>');
	 	i++;
	}while (i <= Math.ceil(xhr.post_count/post_per_page));
	$(".count").first().attr('id', 'active');
}
//Функция отвичаюшяя за работу поиска на 'Вес котолог'
function all_atolog_search(){
	$('.search').submit(function(event) {
		event.preventDefault();
		$.each(ajaxes, function(index, val) {
		 	 val.abort();
	});
	$('.all-catalog__rubricator_body').empty();
	$('.all-catalog__rubricator_body').append('<div class="rubricator_lodaing">Загрузка...</div>');
	let s=$('.search_input').val();
	$('.pages_count').empty();
	$('.products').empty();
	$('.products').append('<div class="body_loading">Загрузка...</div>');
	$('.reset_search').attr('id','load');
	$('.reset_search').ready(function(){reset_search();});
	$.post(ajax, {nonce: nonce,action:'search' ,post_per_page, s}, function(xhr){
		bild_all_catolog_products(xhr);
		bild_all_catolog_pages_count(xhr);
		bild_cats(xhr);
		$('.pc_wrapper').ready(function(){modal_general();basket();});
		$('.count,.cats_text').ready(function(){
			all_cotalog_cats_pages();
		}			
		);
	});
});
	//Поиск на главной странице
if($('.menu_search_input').val()!=''){
	let searched=$('.menu_search_input').val();
	$('.menu_search_input').val('');
	$('.search_input').val(searched);
	$('.search').submit();
}
}
//Функция отвечаюшая за построение котигории	
function bild_cats(xhr,c=0) {
	$('.all-catalog__rubricator_body').empty();
    $('.all-catalog__rubricator_body').append('<div class=\'cats\'><div class=\'cats_all\' id="active">Все</div></div>');
	$.each(xhr.categorys, function(index, val) {
		if(Array.isArray(val)){
			$('.all-catalog__rubricator_body').append('<div class=\'cats\'><div class=\'cats_text\' data-id=\''+index+'\'>'+val[0]+'</div><img class=\'sub_cats_arrow\' id=\'sub_cats_arrow'+c+'\' data-state=\'0\' src=\''+blog_link+'/Imeges/small_white_arrow.png\' onclick=\'sub_cats_arrow('+c+');\'></div>');
			$('.all-catalog__rubricator_body').append('<div id=\'sub_cats_wrapper'+c+'\'class=\'sub_cats_wrapper\'></div>')
			$.each(val[1], function(i, v) {	
				$('#sub_cats_wrapper'+c).append('<div class=\'sub_cats\'><div class=\'cats_text\' data-id=\''+i+'\'>'+v+'</div></div>');
			});
			c++;
		}
		else{
			$('.all-catalog__rubricator_body').append('<div class=\'cats\'><div class=\'cats_text\' data-id=\''+index+'\'>'+val+'</div></div>');
		}	
	});
}
//Отправка на сервер инфу заказа 
function basket_send_order(){
	$('.bascket_ordered').submit(function(event) {
		event.preventDefault();
		let client={};
		$(this).find('input').each(function(index, el) {
			client[$(el).attr('name')]=$(el).val();	
		});
		$('.bascket_ordered_button').attr('id',null);
		$('.bascket_ordered').empty();
		$('.bascket_ordered').append('Загрузка...');
		$('.menu_basket_count').css('display', 'none');
		$.post(ajax, {nonce: nonce,action:'co',client,ordered_goods}, function(xhr) {
			if($final_total_sum==parseInt(xhr)){
				$('.bascket_ordered').empty();
				$('.bascket_ordered').append('<label class="bascket_ordered_text">Ваш заказ принят</br>Мы с вами скоро свяжемся.</br>Спасибо за заказ!</label>')
				$('.menu_basket').one('click', function(event) {
					$('.bascket-modal_wrapper').empty();
					$('.bascket_order').css('display', 'flex');
					$('.bascket-modal_wrapper').css('display', 'none');
					$('.bascket-modal_up').append('<div class="bascket_empty">Корзина пуста</div>');
					$('.bascket_order_sum_spun').empty();
					$('.bascket_order_sum_spun').append('0')
				});				
			}
			else{
				$('.bascket_ordered_button').attr('id',null);
				$('.bascket_ordered').empty();
				$('.bascket_ordered').append('<label class="bascket_ordered_text">Произошла ошибка!!!</be>Позвоните по номеру:'+$('.body_info_contacts_text').text()+'</label>')
				$('.menu_basket').one('click', function(event) {
					$('.bascket-modal_wrapper').empty();
					$('.bascket_order').css('display', 'flex');
					$('.bascket-modal_wrapper').css('display', 'none');
					$('.bascket-modal_up').append('<div class="bascket_empty">Корзина пуста</div>');
					$('.bascket_order_sum_spun').empty();
					$('.bascket_order_sum_spun').append('0')
				});
			}
		});
	});
}
//Функция отвечаюшая за изминение большого изображения 
function pw_big_img_change(xhr){
	$('.modal_pw_center_img-small_galery>img').on('click', function(event) {
		$('#modal_pw_center_img-small_active').attr('id',null);
		$(this).attr('id','modal_pw_center_img-small_active');
		$('.modal_pw_center_img-big>img').attr('src', xhr.product.gallery[$(this).attr('data-id')].big);
	});
	$('.modal_pw_center_img-small>img').on('click',function(event){
		$('#modal_pw_center_img-small_active').attr('id',null);
		$(this).attr('id','modal_pw_center_img-small_active');
		$('.modal_pw_center_img-big>img').attr('src', xhr.product.big_image_src);
	});
}