<!DOCTYPE html>
<html <?php echo language_attributes(); ?>>
<head>
	<meta charset="<?php echo bloginfo('charset'); ?>">
	<title>Mafon.kz</title>
	<link rel="shortcut icon" href="<?php echo bloginfo('template_directory')?>/Imeges/favicon.ico)" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_directory')?>/Styles/style.css">
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" >
    <script  src="<?php echo bloginfo('template_directory')?>/Scripts/jquery-3.5.1.min.js"></script>	
    <script type="text/javascript">
    	var ajax = {'url':'<?php echo admin_url('admin-ajax.php')?>'};
		var blog_link= '<?php echo bloginfo('template_directory')?>';
		var nonce= '<?php echo wp_create_nonce('Mirin')?>';
	</script>
</head>
<body>	
	<header id='header' style="background: url(<?php echo wp_get_attachment_image_url( carbon_get_theme_option('back_ground'),'full'); ?>);background-size: cover; background-position: left top;background-attachment: fixed;background-repeat: no-repeat;">
		<nav class="nav"> 
			<div class="nav_logo"><img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'logo' ),'full'); ?>" alt="" onclick="homePage();slowScroll('#header')"></div>
			<div class="small_screen_cats">Категории</div>
			<div class="menu">
		 		<div class="menu_body">
		 			<div class="memu_icon_wrapper">
			 			<img src="<?php echo bloginfo('template_directory')?>/Imeges/Mafon.kz Menu Icon.png" class="memu_icon">
			 			<div class="menu_basket_count"></div>
		 			</div>
		        	<div class="menu_list">
							<a href="javascript://0" class="menu_link" id="link-catalog" onclick="slowScroll('#catalog');">КАТАЛОГ</a>
							<a href="javascript://0" class="menu_link" id="link-terms" onclick="slowScroll('#terms');">КАК МЫ РАБОТАЕМ ?</a>
							<a href="javascript://0" class="menu_link"  id="link-action" onclick="slowScroll('#action');">СВЯЗАТЬСЯ</a>
		   				 	<div href="" class="menu_search">
		   				 	<input type="text" name="search" placeholder="Поиск" class="menu_search_input">
		   				 		<a><img src="<?php echo bloginfo('template_directory')?>/Imeges/Search_icon.png" alt="" id='menu_search_icon' onclick="seaerchIcon('#menu_search_icon')"></a>
		   				 	</div>
					     	<div class="menu_basket"><img id="menu_basket_icon" src="<?php bloginfo('template_directory')?>/Imeges/Basket_icon.png" alt="" onclick="seaerchIcon('#menu_basket_icon')"><div class="menu_basket_count"></div></div>
					</div>
				</div>
		    </div>
		    <div class="bascket-modal">
		    	<div class="bascket-modal_up">
		    	<div class="bascket_empty">Корзина пуста</div>
				<div class="bascket-modal_wrapper" >
			    </div>
			    </div>
			   <div class="bascket_order">
			    	<button class="bascket_order_button">Заказать</button>
			    	<div class="bascket_order_summ"><span class="bascket_order_sum_spun">0</span>тг</div>	
			    </div>
		    </div>
		</nav>	 
		<div class="welcome_inscription">
			<div class="welcome_inscription_welcome"><img src="<?php bloginfo('template_directory')?>/Imeges/Добро_пожаловать_.png" alt=""></div>
			<div class="welcome_inscription_toshop"><?php echo carbon_get_theme_option('welcome')?></div>				 
		</div>
		<div class="arrow"><img src="<?php bloginfo('template_directory')?>/Imeges/Arrow_.icon.png" alt=""></div>  	 
	</header>
	<div id="all"></div>	
	<main class='main'>
		<div class="advantages" id="advantages">
			<div class="contener" >
			<div class="advantages_speed" id="advantages_some">
				<div class="advantages_speed_icon" id="advantages_icon"><img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'icon_1' ),'full'); ?>" alt=""></div>
				<div class="advantages_speed_heading" id="advantages_heading"><?php echo carbon_get_theme_option('advantage_1_head')?></div>
				<div class="advantages_speed_text" id="advantages_text"><?php echo carbon_get_theme_option('advantage_1_body')?>
				</div>
			</div>
			<div class="advantages_qwality" id="advantages_some">
				<div class="advantages_qwality_icon" id="advantages_icon"><img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option('icon_2' ),'full');?>" alt=""></div>
				<div class="advantages_qwality_heading" id="advantages_heading"><?php echo carbon_get_theme_option('advantage_2_head')?></div>
				<div class="advantages_qwality_text"id="advantages_text"><?php echo carbon_get_theme_option('advantage_2_body')?>
				</div>
			</div>
			<div class="advantages_prise" id="advantages_some">
				<div class="advantages_prise_icon" id="advantages_icon"><img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'icon_3' ),'full'); ?>" alt=""></div>
				<div class="advantages_prise_heading" id="advantages_heading"><?php echo carbon_get_theme_option('advantage_3_head')?></div>
				<div class="advantages_prise_text" id="advantages_text"><?php echo carbon_get_theme_option('advantage_3_body')?>
				</div>
			</div>
			</div>	   		
	   </div>
		<div class="catalog" id="catalog">
			<div class="catalog_wrapper">
		<?php 
		$tax_query[] = array(
    'taxonomy' => 'product_visibility',
    'field'    => 'name',
    'terms'    => 'featured',
    'operator' => 'IN', // or 'NOT IN' to exclude feature products
);
		$args = array(
        'post_type'=>'product',
		'post_status'=>'publish',
		'orderby'=>'date',
		'posts_per_page' => 14,
		'tax_query' => $tax_query
    );
    $wp_query = new WP_Query($args);
   		if($wp_query->have_posts()){
    	while($wp_query->have_posts()){
    		$wp_query->the_post();
    		$product_id= get_the_ID();
    		$product = wc_get_product( $product_id );
    		?>
    			<div class="catalog_wrapper_product-cart" data-id="<?php echo get_the_ID(); ?>">
					   		<div class="catalog_wrapper_product-cart_prise-off" onclick="" data-id="<?php echo get_the_ID(); ?>"><?php 
					   			if( $product->get_sale_price()==""){echo "0";}
					   			else{
					   				echo round(100-($product->get_sale_price()/($product->get_regular_price()/100)), 0); 
					   			}
					   		?>%</div>
					   		<div class="catalog_wrapper_product-cart_body" >
					   			<div class="pc_wrapper" onclick="" data-id="<?php echo get_the_ID(); ?>">
							   		<div class="catalog_wrapper_product-cart_body_imeg" >
							   			<img src="<?php echo get_the_post_thumbnail_url( null,'large' ) ?>">
										<div class="catalog_wrapper_product-cart_body_name" style="position: absolute;bottom: 25%;"><?php echo $product->get_name(); ?></div>
							   		</div>
									<div class="catalog_wrapper_product-cart_body_property">
										<a href="javascript://0" class="catalog_wrapper_product-cart_body_property_more">Подробнее..</a>
										<div class="catalog_wrapper_product-cart_body_property_prise">
										<div class="catalog_wrapper_product-cart_body_property_prise_old"><?php if( $product->get_sale_price()==""){echo "";} else{echo $product->get_regular_price();}?></div>
								   		<div class="catalog_wrapper_product-cart_body_property_prise_new"><?php  if( $product->get_sale_price()==""){echo $product->get_regular_price();} else{echo $product->get_sale_price();} ?></div>
								   	</div>
									</div>
								</div>   	
						   		<button class="catalog_wrapper_product-cart_body_button" onclick="null"><img src="<?php bloginfo('template_directory')?>/Imeges/Basket_icon.png" alt=""></button>
						   	</div>			   	
				</div>
    		<?php
    	}
    	}
    	else{
    		echo "Ничего не найдено";
    	}
    ?>
			</div>						  		   			   	   			   			   			   		
			<a class="catalog_more-button"><div class="catalog_more-button_text" onclick="allCotolog();">Весь кталог</div></a>
		</div>
		<div class="shodow-box">
			<div class="terms" id="terms">
				<div class="terms_delivering" id="delivering">
					<div class="terms_delivering_heading">
						<div class="terms_delivering_heading_text">Доставка</div>
						<div class="terms_delivering_heading_menu">
							<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'delivering_img' ),'full'); ?>" class="terms_delivering_heading_menu_img-1">
							<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'seting_img' ),'full'); ?>" class="terms_delivering_heading_menu_img-2" onclick="slowScroll('#seting')" title="Установка">
							<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'paying_img' ),'full'); ?>" class="terms_delivering_heading_menu_img-3" onclick="slowScroll('#paying')" title="Оплата">
						</div>
					</div>
					<div class="terms_delivering_body" id="terms_body">
						<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'delivering_img' ),'full'); ?>" alt="">
						<div class="terms_delivering_body_text" id="terms_body_text"><?php echo carbon_get_theme_option('delivering_text')?></div>
					</div>			
				</div>
				<div class="terms_seting" id="seting">				
					<div class="terms_seting_heading">
						<div class="terms_seting_heading_text">Установка</div>
						<div class="terms_seting_heading_menu">
							<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'delivering_img' ),'full'); ?>" class="terms_seting_heading_menu_img-1" onclick="slowScroll('#delivering')" title="Доставка">
							<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'seting_img' ),'full'); ?>" class="terms_seting_heading_menu_img-2">
							<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'paying_img' ),'full'); ?>" class="terms_seting_heading_menu_img-3" onclick="slowScroll('#paying')" title="Оплата">
						</div>
					</div>
					<div class="terms_seting_body" id="terms_body">
						<div class="terms_seting_body_text" id="terms_body_text"><?php echo carbon_get_theme_option('seting_text')?></div>
						<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'seting_img' ),'full'); ?>" alt="">
					</div>
				</div>
				<div class="terms_paying" id="paying">				
					<div class="terms_paying_heading">
						<div class="terms_paying_heading_text">Оплата</div>
						<div class="terms_paying_heading_menu">
							<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'delivering_img' ),'full'); ?>" class="terms_paying_heading_menu_img-1" onclick="slowScroll('#delivering')" title="Доставка">
							<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'seting_img' ),'full'); ?>" class="terms_paying_heading_menu_img-2" onclick="slowScroll('#seting')" title="Установка">
							<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'paying_img' ),'full'); ?>" class="terms_paying_heading_menu_img-3">
						</div>
					</div>
					<div class="terms_paying_body" id="terms_body">
						<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'paying_img' ),'full'); ?>" alt="">
						<div class="terms_paying_body_text" id="terms_body_text"><?php echo carbon_get_theme_option('paying_text')?></div>
					</div>
				</div>	
			</div>
			<div class="action" id="action">
				<div class="action_wrapper">
					<div id="map">
						<script src="https://maps.api.2gis.ru/2.0/loader.js?pkg=full"></script>
						<script type="text/javascript">
						    var map;

						    DG.then(function () {
						        map = DG.map('map', {
						            center: [<?php echo carbon_get_theme_option('location')[lat];?>, <?php echo carbon_get_theme_option('location')[lng];?>],
						            zoom: 15
						        });
						        DG.marker([<?php echo carbon_get_theme_option('location')[lat];?>,  <?php echo carbon_get_theme_option('location')[lng];?>]).addTo(map).bindPopup('<a href="<?php echo carbon_get_theme_option('location_link');?>">Открыть на карте</a>');
						    });
						</script>
					</div>
					<form class="action_wrapper_form">
						<?php echo do_shortcode('[contact-form-7 id="176" title="Контактная форма 1"]'); ?>
						<!--<h1>Остались вопросы</h1>
						<input type="text" placeholder="Имя" class="action_wrapper_form_name">
						<input type="tel" placeholder="+7" class="action_wrapper_form_tel">
						<button type="submit" class="action_wrapper_form_submit" >Перезвонить мне</button>-->
					</form>
				</div>	
			</div>
		</div>
	</main>
	<footer id="footer">	
		<div class="body">
				<div class="body_logo">
					<img src="<?php echo wp_get_attachment_image_url( carbon_get_theme_option( 'logo' ),'full'); ?>" class="body_logo_img">
					<div class="body_logo_text"><?php echo carbon_get_theme_option('footer_name');?></div>
				</div>
				<div class="body_info">
					<div class="body_info_contacts">
						<div class="body_info_contacts_heading" id="body_info_heading">Контакты:</div>
						<div class="body_info_contacts_text" id="body_info_text"><?php echo carbon_get_theme_option('footer_tel');?></div>
					</div>				
					<div class="body_info_adres">
						<div class="body_info_adres_heading" id="body_info_heading">Адрес:</div>
						<div class="body_info_adres_text" id="body_info_text"><?php echo carbon_get_theme_option('footer_adres');?></div>
					</div>
				</div>
				<div class="body_cn">
					<div class="body_cn_heading">Мы в соцальных сетях:</div>
					<div class="body_cn_logos">
						<a href="<?php echo carbon_get_theme_option('instagram');?>"><img src="<?php bloginfo('template_directory')?>/Imeges/inst.png" alt=""></a>
						<a href="<?php echo carbon_get_theme_option('whats_app');?>"><img src="<?php bloginfo('template_directory')?>/Imeges/wa.png" alt=""></a>
						<a href="<?php echo carbon_get_theme_option('vk');?>"><img src="<?php bloginfo('template_directory')?>/Imeges/vk.png" alt=""></a>
						<a href="<?php echo carbon_get_theme_option('facebook');?>"><img src="<?php bloginfo('template_directory')?>/Imeges/fb.png" alt=""></a>
					</div>
				</div>
		</div>
		<div class="insan-wd">Сайт создан студией Insan Web Devolopment</div>
	</footer>
	<div class="modal">
		<div class="modal_pw">
			<a href="javascript://0" class="modal_pw_x" onclick="closeModal();">X</a>
			<div class="modal_pw_center">
				<div class="modal_pw_center_img" >
				<div class="modal_pw_center_img-big"><img src="<?php bloginfo('template_directory')?>/Imeges/Слой_1_копия_5.png"></div>
				<div class="modal_pw_center_img-small"><img id="modal_pw_center_img-small_active" src="<?php bloginfo('template_directory')?>/Imeges/Слой_1_копия_5.png"><div class="modal_pw_center_img-small_galery"></div></div>
				</div>
				<div class="modal_pw_center_text">
				<div class="modal_pw_center_text_name">Загрузка...</div>
				<div class="modal_pw_center_text_characters">
				</div>
			</div>
				
			</div>
			<div class="modal_pw_end">
				<a href="javascript://0" class="modal_pw_end_all" onclick="openAll();">Все характиристики</a>
				<a href="javascript://0" class="modal_pw_end_reviews" onclick="closePW();openReviews();">Отзывы</a>
				<div class="modal_pw_end_prise">
					<div class="modal_pw_end_prise_old"></div>
					<div class="modal_pw_end_prise_new"></div>
					<button class="modal_pw_end_prise_button"><img src="<?php bloginfo('template_directory')?>/Imeges/Basket_icon.png" alt=""></button>
				</div>
			</div>
		</div>
		<div id="all-char">
			<div class="modal_all_text">Характиристики</div>
			<a href="javascript://0" onclick="closeAll();"><img src="<?php bloginfo('template_directory')?>/Imeges/back_arrow_icon.png" alt=""></a>
			<div class="table">
			Загрузка...		
			</div>
		</div>
		<div class="modal_reviews" id="reviews">
			<div class="modal_reviews_head">Отзывы</div>
			<a href="javascript://0" class="modal_reviews_x" onclick="closeReviews();openPW();"><img src="<?php bloginfo('template_directory')?>/Imeges/back_arrow_icon.png" alt=""></a>
			<button class="modal_reviews_button" onclick="openForms()">Оставить отзыв</button>
			<div class="modal_reviews_body">
			</div>
		</div>
		<form class="modal__forms" id="forms" method="post">
			<a href="javascript://0" class="modal__forms__x" onclick="closeForms()"><img src="<?php bloginfo('template_directory')?>/Imeges/back_arrow_icon.png" alt=""></a>
			<input type="text" placeholder="Имя" class="modal__forms__name" required name="name">
			<input type="email" placeholder="E-mail" class="modal__forms__email" required name="email">
			<input type="tel" placeholder="Номер без +7" class="modal__forms__tel" pattern="[0-9]{10}" required name="tel"> 
			<input type="text" placeholder="Отзыв" class="modal__forms__text" required name="content">
			<button type="submit" class="modal__forms__button" >Оставить отзыв</button>			
		</form>
	</div>
</body>
<script type="text/javascript" src="<?php echo bloginfo('template_directory')?>/Scripts/ajax.js"></script>
<script type="text/javascript" src="<?php echo bloginfo('template_directory')?>/Scripts/page.js"></script>
<script type="text/javascript" src="<?php echo bloginfo('template_directory')?>/Scripts/nav-bar.js"></script>
<script type="text/javascript" src="<?php echo bloginfo('template_directory')?>/Scripts/catalog&footer.js"></script>
</html>