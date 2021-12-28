function slowScroll(id) {
	$scrollTop = $(id).offset ().top
    $('html, body').animate ({
   	scrollTop: $scrollTop
	}, 500);
	return false;
};
$(window).on('scroll', function(){
	if($(window).scrollTop()){
		$('.nav').attr('id','onscroll');
		$('.memu_icon').css('bottom','25%');
	}
	else{
		$('.nav').attr('id',null);
		$('.memu_icon').css('bottom','6%')
	}	
});
$('.menu_search_input').focus(function(){
	//лля поиска
	$('.menu_search').attr({'id': 'search-focused',});
});
$('.menu_search_input').blur(function() {
	//лля поиска
	$('.menu_search').attr({'id': null});
});
$('.menu_search').hover(function() {
	//лля поиска
	$('#menu_search_icon').css('width', $('#menu_search_icon').width()+2);
}, function() {
	$('#menu_search_icon').css('width', $('#menu_search_icon').width()-2);
});
function seaerchIcon(id){
	//лля поиска и корзины
	$(id).animate({
		width : $(id).width (),
		width  : $(id).width ()-2},
		50, function() {
			$(id).animate({
			width  : $(id).width (),
			width  : $(id).width ()+2},
			100
		);
	});
}; 
$('#menu_basket_icon').hover(function() { 
//лля корзины
	$(this).css('width', $(this).width()+2);
}, function() {
	$(this).css('width', $(this).width()-2);
});
//затемнение ссылок в меню
$(window).on('scroll', function() {
	if($('#catalog').offset().top-1 < $(window).scrollTop() && $(window).scrollTop()<$('#catalog').offset().top-1+$('#catalog').height()){
		$('#link-catalog').css('color', '#aeabab');
	}
	else{
		$('#link-catalog').css('color', ' #ffffff');
	}
	if($('#terms').offset().top-1 < $(window).scrollTop() && $(window).scrollTop()<$('#terms').offset().top-1+$('#terms').height()){
		$('#link-terms').css('color', '#aeabab');
	}
	else{
		$('#link-terms').css('color', ' #ffffff');
	}
	if($('#action').offset().top-1 < $(window).scrollTop() && $(window).scrollTop()<$('#action').offset().top-1+$('#action').height()){
		$('#link-action').css('color', '#aeabab');
	}
	else{
		$('#link-action').css('color', ' #ffffff');
	}

});
//Поиска на главной странице
$('#menu_search_icon').on('click', function(event) {
	allCotolog();
});
//Карзина
function basket_goods_count_minus(event){
	event.preventDefault();
	if(parseInt($(event.target).parents('.bascket-modal_counter_botton_wrapper').siblings('.bascket-modal_counter_input').val())<=1){return true}
	else{$(event.target).parents('.bascket-modal_counter_botton_wrapper').siblings('.bascket-modal_counter_input').val(parseInt($(event.target).parents('.bascket-modal_counter_botton_wrapper').siblings('.bascket-modal_counter_input').val())-1);}
	count_total_sum();
}	
function basket_goods_count_plus(event){
	event.preventDefault();
	$(event.target).parents('.bascket-modal_counter_botton_wrapper').siblings('.bascket-modal_counter_input').val(parseInt($(event.target).parents('.bascket-modal_counter_botton_wrapper').siblings('.bascket-modal_counter_input').val())+1);
	count_total_sum();
}
function basket_goods_remove(event){
	$(event.target).parents('.bascket-modal_cart').remove();
	if($('.bascket-modal_wrapper').children().length==0){
		$('.bascket-modal_wrapper').css('display', 'none');
		$('.bascket-modal_up').append('<div class="bascket_empty">Корзина пуста</div>');
	}
	count_total_sum();
}
function count_total_sum() {
		$('.bascket_order_sum_spun').empty();
		$total_sum=0;
		$total_count=0;
		$total_prise=0;
		$circle_count=0;
		if($('.bascket-modal_wrapper').children().length==0){
			$('.bascket_order_sum_spun').append(0);
			$('.menu_basket_count').empty();
			$('.menu_basket_count').css('display', 'none');
		}
		if($('.bascket-modal_wrapper').children().length==1){
			$total_sum=parseInt($('.bascket-modal_wrapper').find('.bascket-modal_prise_new').text());
			$total_count=parseInt($('.bascket-modal_wrapper').find('.bascket-modal_counter_input').val());
			$total_sum=$total_sum*$total_count;
			$('.bascket_order_sum_spun').append($total_sum);
			$('.menu_basket_count').empty();
			$('.menu_basket_count').css('display', 'flex');
			$('.menu_basket_count').append($total_count);
		}
		if($('.bascket-modal_wrapper').children().length>1){
			$c=0;
			while($c<$('.bascket-modal_wrapper').children().length){
				$total_prise=parseInt($($('.bascket-modal_wrapper').find('.bascket-modal_prise_new')[$c]).text());
				$total_count=parseInt($($('.bascket-modal_wrapper').find('.bascket-modal_counter_input')[$c]).val());
				$total_sum=$total_sum+$total_prise*$total_count;
				$circle_count=$circle_count+$total_count;
				$c++;
			}
			$('.bascket_order_sum_spun').append($total_sum);
			$('.menu_basket_count').empty();
			$('.menu_basket_count').css('display', 'flex');
			$('.menu_basket_count').append($circle_count);
		}
}
function	basket_opening(){
	$('.menu_basket').on('click',function() {
		if($('.bascket-modal').attr('id')!='opened'){
			$('.bascket-modal').attr('id', 'opened');
			$('.bascket_ordered_button').attr('id','opened');
		}
		else{
			$('.bascket-modal').attr('id',null);
			$('.bascket_ordered_button').attr('id',null);
		}
	});		
}
function adding_to_basket($this){
	let product={};
	product['id']=$($this).closest('.catalog_wrapper_product-cart').attr('data-id');
	product['img']=$($this).closest('.catalog_wrapper_product-cart').find('.catalog_wrapper_product-cart_body_imeg>img').attr('src');
	product['name']=$($this).closest('.catalog_wrapper_product-cart').find('.catalog_wrapper_product-cart_body_name').text();
	product['prise_new']=$($this).closest('.catalog_wrapper_product-cart').find('.catalog_wrapper_product-cart_body_property_prise_new').text();
	product['prise_old']=$($this).closest('.catalog_wrapper_product-cart').find('.catalog_wrapper_product-cart_body_property_prise_old').text();
	if($('.bascket-modal_wrapper').find('#bascket-modal_cart'+product['id']).attr('data-id')==product['id']){
		$('.bascket-modal_wrapper').find('#bascket-modal_cart'+product['id']).find('.bascket-modal_counter_input').val(parseInt($('.bascket-modal_wrapper').find('#bascket-modal_cart'+product['id']).find('.bascket-modal_counter_input').val())+1);
		$('.bascket-modal_wrapper').ready(function(){count_total_sum()});
		return false;
	}
	$('.bascket_empty').remove();
	$('.bascket-modal_wrapper').css('display', 'block');
	$('.bascket-modal_wrapper').append('<div class="bascket-modal_cart" id="bascket-modal_cart'+product['id']+'" data-id="'+product['id']+'"><div class="bascket-modal_body"><div class="bascket-modal_img"><img src="'+product['img']+'"></div><div class="bascket-modal_cart_wrapper"><div class="bascket-modal_name">'+product['name']+'</div><div class="bascket-modal_prise_wrapper"><div class="bascket-modal_prise_old">'+product['prise_old']+'</div><div class="bascket-modal_prise_new">'+product['prise_new']+'</div></div></div></div><form class="bascket-modal_counter"><div class="bascket-modal_counter_text">Количесво:</div><input type="text" class="bascket-modal_counter_input" value="1"><div class="bascket-modal_counter_botton_wrapper"><button id="plus" class="bascket-modal_counter_botton" onclick="basket_goods_count_plus(event);">+</button><button id="minus" class="bascket-modal_counter_botton" onclick="basket_goods_count_minus(event);" >-</button></div>	</form><div class="bascket-modal_remove" onclick="basket_goods_remove(event);"><img src="'+blog_link+'/Imeges/delete_icon.png" alt=""></div></div>');
	count_total_sum();
}
function basket() {
	$('.catalog_wrapper_product-cart_body_button').on('click', function(event) {
		$this=this;
		adding_to_basket($this);
		count_total_sum();
	});
}
function basket_modal_pw() {
	$('.modal_pw_end_prise_button').on('click', function(event) {
		$data_id=$(this).attr('data-id');
		$('body').find('.catalog_wrapper_product-cart').each(function(index, el) {
			if($(el).attr('data-id')==$data_id){
				adding_to_basket($(el).find('.catalog_wrapper_product-cart_body_button'));
				return false;
			}			
		});
	});
}
let ordered_goods={};
$final_total_sum=0;
function basket_order() {
	$('.bascket_order_button').on('click', function(event) {
		 $c=0;
		 if($('.bascket-modal_wrapper').find('.bascket-modal_cart').length==0){
		 	return false;
		 }
		 $('.bascket-modal_wrapper').find('.bascket-modal_cart').each(function(index, el) {
		 	  ordered_goods[$c]={};
		 	  ordered_goods[$c]['id']=$(el).attr('data-id');
		 	  ordered_goods[$c]['count']=$(el).find('.bascket-modal_counter_input').val();
		 	 $c++;
		 });
		$final_total_sum=parseInt($('.bascket_order_sum_spun').text());
	 	$('.bascket-modal_wrapper').empty();
	 	$('.bascket-modal_wrapper').append('<form class="bascket_ordered"><label class="bascket_ordered_text">Введите ваши данные, чтобы обсудить с вам детали заказа</label><input type="text" placeholder="Имя"class="bascket_ordered_name" required name="name"><input type="tel" placeholder="+7" required pattern="[0-9]{10}" name="tel" class="bascket_ordered_tel"><button type="submit"class="bascket_ordered_button">Обсудить детали</button></form>')
		$('.bascket-modal_wrapper').css('display', 'block');
		$('.bascket_order').css('display', 'none');
		$('.bascket_ordered_button').attr('id','opened');
		$('.catalog_wrapper_product-cart_body_button').one('click', function(event) {
			$('.bascket_order').css('display', 'flex');
			$('.bascket_ordered').remove();
			$('.bascket_ordered_button').attr('id',null);
			count_total_sum();
		});
		basket_send_order();
	});
}
basket();
basket_opening();
basket_order();
basket_modal_pw();