<?php
//Carbon Filds
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
function crb_attach_theme_options() {
    Container::make( 'theme_options','Управление' )
        ->add_tab( 'Шапка сайта', [
            Field::make( 'image', 'logo', 'Логотип' ),
            Field::make( 'text', 'welcome', 'Текст приветствия' ),
            Field::make( 'image', 'back_ground', 'Фоновая картинка' ),
        ])
        ->add_tab( 'О нас', [
            Field::make("separator", "advantage_1", "Качество-1"),
            Field::make( 'image', 'icon_1', 'Иконка' )->set_width(50),
            Field::make( 'text', 'advantage_1_head', 'Заголовок' )->set_width(50),
            Field::make( 'text', 'advantage_1_body', 'Текст' ),
            Field::make("separator", "advantage_2", "Качество-2"),
            Field::make( 'image', 'icon_2', 'Иконка' )->set_width(50),
            Field::make( 'text', 'advantage_2_head', 'Заголовок' )->set_width(50),
            Field::make( 'text', 'advantage_2_body', 'Текст' ),
            Field::make("separator", "advantage_3", "Качество-3"),
            Field::make( 'image', 'icon_3', 'Иконка' )->set_width(50),
            Field::make( 'text', 'advantage_3_head', 'Заголовок' )->set_width(50),
            Field::make( 'text', 'advantage_3_body', 'Текст' ),
        ])
        ->add_tab( 'Как мы работаем?', [
        	Field::make("separator", "delivering", "Доставка"),
	        	Field::make( 'image', 'delivering_img', 'Картинка' )->set_width(50),
	            Field::make( 'rich_text', 'delivering_text', 'Описание' )->set_width(50),
        	Field::make("separator", "seting", "Установка"),
	        	Field::make( 'image', 'seting_img', 'Картинка' )->set_width(50),
	            Field::make( 'rich_text', 'seting_text', 'Описание' )->set_width(50),
        	Field::make("separator", "paying", "Оплата"),
	        	Field::make( 'image', 'paying_img', 'Картинка' )->set_width(50),
	            Field::make( 'rich_text', 'paying_text', 'Описание' )->set_width(50),
        ])
        ->add_tab( 'Карта', [
            Field::make("map", "location", "Местоположение Компании")->set_position( 43.24027114, 76.91568017, 11)->set_required(true),
            Field::make( 'text', 'location_link', 'Ссылка на адрес компании на карте' )->set_required(true),
        ])
        ->add_tab( 'Подвал сайта', [
           Field::make( 'text', 'footer_name', 'Имя сайта' ),
           Field::make( 'text', 'footer_tel', 'Телефон' )->set_width(50),
           Field::make( 'text', 'footer_adres', 'Адрес компании' )->set_width(50),
           Field::make("separator", "cn", "Ссылки на cоц.сети"),
           Field::make( 'text', 'instagram', 'Instagram' )->set_width(25),
           Field::make( 'text', 'whats_app', 'Whats app' )->set_width(25),
           Field::make( 'text', 'vk', 'VK' )->set_width(25),
           Field::make( 'text', 'facebook', 'Facebook' )->set_width(25),
        ]);
}
add_action('wp_ajax_pw','product_window');
add_action('wp_ajax_nopriv_pw','product_window');
function product_window(){
	check_ajax_referer( 'Mirin' , 'nonce' , true );
	$product = wc_get_product($_POST[id]);
	$old_prise;
	$new_prise;
	if( $product->get_sale_price()!=""){$old_prise=$product->get_regular_price();}
	if( $product->get_sale_price()==""){$new_prise=$product->get_regular_price();} else{$new_prise=$product->get_sale_price();} 
	$comments=get_comments( array('post_type'=>'product','post_id'=>$_POST[id]));
	$good['product']= array(
		name=>$product->get_name(),
		old_prise=>$old_prise,
		new_price=>$new_prise,
		big_image_src=>wp_get_attachment_image_url( $product->get_image_id(),'full'),
		small_image_src=>wp_get_attachment_image_url( $product->get_image_id(),'thumbnail'),
		short_description=>$product->get_short_description(),
		description=>$product->get_description()
	);
	$k=0;
	$good['product']['gallery']=array();
	foreach ($product->get_gallery_image_ids() as $key => $value){
		$good['product']['gallery'][]=array(
			'big'=>wp_get_attachment_image_url( $value,'full'),
			'small'=>wp_get_attachment_image_url( $value,'thumbnail')
		);
	}
	$k=0;
	foreach ( $comments as $comment ){
    	$data[$k]=array(
			author=>$comment->comment_author,
			date=>$comment->comment_date,
			content=>$comment->comment_content
    		);
    	$k++;
    	}
    $good['reviews']=$data;	
	wp_send_json($good);		
	wp_die();
	wp_reset_query();
}
add_action('wp_ajax_ar','add_review');
add_action('wp_ajax_nopriv_ar','add_review');
function add_review(){
	check_ajax_referer( 'Mirin' , 'nonce' , true );
	$userdata = array(
		'user_pass'       => $_POST[inputs][email], // обязательно
		'user_login'      => $_POST[inputs][email], // обязательно
		'user_email'      => $_POST[inputs][email],
		'first_name'      => $_POST[inputs][name],
		'last_name'       => $_POST[inputs][tel],
		'user_registered' => current_time('mysql')
	);
	$user_id=wp_insert_user($userdata);
	$comentdata = [
			'comment_post_ID'      => $_POST[id],
			'comment_author'       => $_POST[inputs][name],
			'comment_author_email' => $_POST[inputs][email],
			'comment_content'      => $_POST[inputs][content],
			'comment_type'         => 'comment',
			'user_id'              => $user_id,
			'comment_date'         => current_time('mysql')
		];
	wp_insert_comment(wp_slash($comentdata));	
	$comments=get_comments( array('post_type'=>'product','post_id'=>$_POST[id]));	
	$k=0;
	foreach ( $comments as $comment ){
    	$data[$k]=array(
			author=>$comment->comment_author,
			date=>$comment->comment_date,
			content=>$comment->comment_content
    		);
    	$k++;
    }
    $good['reviews']=$data;		
	wp_send_json($good);
	wp_die();
	wp_reset_query();
}
add_action('wp_ajax_ac_ac','all_cotalog_all_catigory');
add_action('wp_ajax_nopriv_ac_ac','all_cotalog_all_catigory');
function all_cotalog_all_catigory(){
	check_ajax_referer( 'Mirin' , 'nonce' , true );
	$taxonomy     = 'product_cat';
  $orderby      = 'name';  
  $show_count   = 0;      // 1 for yes, 0 for no
  $pad_counts   = 0;      // 1 for yes, 0 for no
  $hierarchical = 1;      // 1 for yes, 0 for no  
  $title        = '';  
  $empty        = 1;

  $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
  );
 $all_categories = get_categories( $args );
 $data['categorys']=array();
 $sc=array();
$test=array();
$simular_sub_cats_calculator=1;
 foreach ($all_categories as $cat) {
 	if ($cat->parent == 0) {
 		$data['categorys'][$cat->cat_ID]=$cat->name;
 		$simular_sub_cats_calculator=1;
 	}
 	else{
 		if( array_key_exists($cat->name,$sc)){
	        $sc[$cat->name.'-'.$simular_sub_cats_calculator]=array('id'=>$cat->cat_ID,'pid'=>$cat->parent);
	    	$simular_sub_cats_calculator++;
   		}
   		else{
   			$sc[$cat->name]=array('id'=>$cat->cat_ID,'pid'=>$cat->parent);
   			$simular_sub_cats_calculator=1;
   		}
 	}
}
foreach ($data['categorys'] as $key => $value) {
	foreach ($sc as $k=>$v) {
		if($key==$v['pid']){
			if(array_key_exists(1,$data['categorys'][$v['pid']])){
			$data['categorys'][$v['pid']][1][$v['id']]=$k;
			}
			else{
			$data['categorys'][$v['pid']]=array($value,array());
			$data['categorys'][$v['pid']][1][$v['id']]=$k;
			}
		}
	}
}
	wp_send_json($data);
	wp_die();
	wp_reset_query();
}
add_action('wp_ajax_ac_lap','all_cotalog_load_all_products');
add_action('wp_ajax_nopriv_ac_lap','all_cotalog_load_all_products');
function all_cotalog_load_all_products(){
	check_ajax_referer( 'Mirin' , 'nonce' , true );
	$args = array(
		'posts_per_page' => -1,
        'post_type'=>'product',
		'post_status'=>'publish',
		'orderby'=>'comment_count',
    );
    $wp_query = new WP_Query($args);
	$data['post_count']=$wp_query->post_count;
	$args = array(
        'post_type'=>'product',
		'post_status'=>'publish',
		'orderby' => array('comment_count' => 'DESC', 'date' => 'DESC' ),
		'posts_per_page' => $_POST['post_per_page'],
		's' => $_POST['s']
    );
    $wp_query = new WP_Query($args);
    $data['products']=array();
    $c=0;
    while($wp_query->have_posts()){
	    $wp_query->the_post();
	   	$product_id= get_the_ID();
	    $product = wc_get_product( $product_id );
	    $data['products'][$c]['id']=$product_id;
	    $data['products'][$c]['img']= get_the_post_thumbnail_url( null,'large' );
	    $data['products'][$c]['name']=$product->get_name();
	    if( $product->get_sale_price()==""){
	    	$data['products'][$c]['prise_old']="";} else{$data['products'][$c]['prise_old']=$product->get_regular_price();
		}
	    if( $product->get_sale_price()==""){
	    	$data['products'][$c]['peise_new']=$product->get_regular_price();} else{$data['products'][$c]['peise_new']=$product->get_sale_price();
		}
		$c++;
	}
   	wp_send_json($data);
	wp_die();
	wp_reset_query();
}   	
add_action('wp_ajax_ac_lp','all_cotalog_load_pages');	
add_action('wp_ajax_nopriv_ac_lp','all_cotalog_load_pages');		
function all_cotalog_load_pages(){
	check_ajax_referer( 'Mirin' , 'nonce' , true );
	if(array_key_exists('cat',$_POST)){
		if(array_key_exists('sort',$_POST)){
	    $args=array('post_type'=>'product',
			'post_status'=>'publish',
			'offset' => $_POST['offset'],
			'posts_per_page' => $_POST['post_per_page'],
	    	'tax_query'	=> array(
		        array(
		            'taxonomy'      => 'product_cat',
		            'field' => 'term_id', 
		            'terms'         => $_POST['cat'],
		            'operator'      => 'IN' 
	        	)
	        ),
	        'orderby' => 'meta_value_num',
	        'meta_key' => '_price',
	        'order'=> $_POST['sort'],
	        's' => $_POST['s']
	    	);
	    }
		else{
	       $args=array( 'post_type'=>'product',
			'post_status'=>'publish',
			'orderby'=> array('comment_count' => 'DESC', 'date' => 'DESC' ),
			'offset' => $_POST['offset'],
			'posts_per_page' => $_POST['post_per_page'],
	    	'tax_query'	=> array(
		        array(
		            'taxonomy'      => 'product_cat',
		            'field' => 'term_id', 
		            'terms'         => $_POST['cat'],
		            'operator'      => 'IN' 
	        	)
	        ),
	        's' => $_POST['s']
	        );	
	    }
	}
	else{
		if(array_key_exists('sort',$_POST)){

	        $args=array('post_type'=>'product',
			'post_status'=>'publish',
			'offset' => $_POST['offset'],
			'posts_per_page' => $_POST['post_per_page'],
			'orderby' => 'meta_value_num',
			'meta_key' => '_price',
			'order'=> $_POST['sort'],
			's' => $_POST['s']
			);	
		}
		else{
	        $args=array('post_type'=>'product',
			'post_status'=>'publish',
			'orderby'=> array('comment_count' => 'DESC', 'date' => 'DESC' ),
			'offset' => $_POST['offset'],
			'posts_per_page' => $_POST['post_per_page'],
			's' => $_POST['s']
			);	
		}
	}
    $wp_query = new WP_Query($args);
        $data['products']=array();
    $c=0;
    while($wp_query->have_posts()){
	    $wp_query->the_post();
	   	$product_id= get_the_ID();
	    $product = wc_get_product( $product_id );
	    $data['products'][$c]['id']=$product_id;
	    $data['products'][$c]['img']= get_the_post_thumbnail_url( null,'large' );
	    $data['products'][$c]['name']=$product->get_name();
	    if( $product->get_sale_price()==""){
	    	$data['products'][$c]['prise_old']="";} else{$data['products'][$c]['prise_old']=$product->get_regular_price();
		}
	    if( $product->get_sale_price()==""){
	    	$data['products'][$c]['peise_new']=$product->get_regular_price();} else{$data['products'][$c]['peise_new']=$product->get_sale_price();
		}
		$c++;
	}
    wp_send_json($data);
	wp_die();
	wp_reset_query();
}
add_action('wp_ajax_ac_lc','all_cotalog_load_catigorys');	
add_action('wp_ajax_nopriv_ac_lc','all_cotalog_load_catigorys');		
function all_cotalog_load_catigorys(){
	check_ajax_referer( 'Mirin' , 'nonce' , true );
	$args = array(
		'posts_per_page' => -1,
        'post_type'=>'product',
		'post_status'=>'publish',
		'orderby'=>'comment_count',
		'tax_query'	=> array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field' => 'term_id', 
	            'terms'         => $_POST['cat'],
	            'operator'      => 'IN' 
        	)
        ),
        's' => $_POST['s']	

    );
    $wp_query = new WP_Query($args);
	$data['post_count']=$wp_query->post_count;
	$args = array(
        'post_type'=>'product',
		'post_status'=>'publish',
		'orderby'=> array('comment_count' => 'DESC', 'date' => 'DESC' ),
		'posts_per_page' => $_POST['post_per_page'],
    	'tax_query'	=> array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field' => 'term_id', 
	            'terms'         => $_POST['cat'],
	            'operator'      => 'IN' 
        	)
        ),
        's' => $_POST['s']		
	);
    $wp_query = new WP_Query($args);
        $data['products']=array();
    $c=0;
    while($wp_query->have_posts()){
	    $wp_query->the_post();
	   	$product_id= get_the_ID();
	    $product = wc_get_product( $product_id );
	    $data['products'][$c]['id']=$product_id;
	    $data['products'][$c]['img']= get_the_post_thumbnail_url( null,'large' );
	    $data['products'][$c]['name']=$product->get_name();
	    if( $product->get_sale_price()==""){
	    	$data['products'][$c]['prise_old']="";} else{$data['products'][$c]['prise_old']=$product->get_regular_price();
		}
	    if( $product->get_sale_price()==""){
	    	$data['products'][$c]['peise_new']=$product->get_regular_price();} else{$data['products'][$c]['peise_new']=$product->get_sale_price();
		}
		$c++;
	}
    wp_send_json($data);
	wp_die();
	wp_reset_query();
}
add_action('wp_ajax_search','all_cotalog_search');
add_action('wp_ajax_nopriv_search','all_cotalog_search');
function all_cotalog_search(){
	check_ajax_referer( 'Mirin' , 'nonce' , true );
	$args = array(
		'posts_per_page' => -1,
        'post_type'=>'product',
		'post_status'=>'publish',
		'orderby'=>'comment_count',
		's' => $_POST['s']
    );
    $wp_query = new WP_Query($args);
	$data['post_count']=$wp_query->post_count;
	$args = array(
        'post_type'=>'product',
		'post_status'=>'publish',
		'orderby' => array('comment_count' => 'DESC', 'date' => 'DESC' ),
		'posts_per_page' => $_POST['post_per_page'],
		's' => $_POST['s']
    );
    $wp_query = new WP_Query($args);
    $data['products']=array();
    $c=0;
    $include=array();
    while($wp_query->have_posts()){
	    $wp_query->the_post();
	   	$product_id= get_the_ID();
	    $product = wc_get_product( $product_id );
	    $data['products'][$c]['id']=$product_id;
	    $data['products'][$c]['img']= get_the_post_thumbnail_url( null,'large' );
	    $data['products'][$c]['name']=$product->get_name();
	    if( $product->get_sale_price()==""){
	    	$data['products'][$c]['prise_old']="";} else{$data['products'][$c]['prise_old']=$product->get_regular_price();
		}
	    if( $product->get_sale_price()==""){
	    	$data['products'][$c]['peise_new']=$product->get_regular_price();} else{$data['products'][$c]['peise_new']=$product->get_sale_price();
		}
		$include[]=$product->get_category_ids()[0];
		$c++;
	}
	$include=array_unique($include);

  $taxonomy     = 'product_cat';
  $orderby      = 'name';  
  $show_count   = 0;      // 1 for yes, 0 for no
  $pad_counts   = 0;      // 1 for yes, 0 for no
  $hierarchical = 1;      // 1 for yes, 0 for no  
  $title        = '';  
  $empty        = 1;

  $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty,
         'include' => $include
  );
 $all_categories = get_categories( $args );
 $data['categorys']=array();
 $sc=array();
$for_search=array();
$simular_sub_cats_calculator=1;
 foreach ($all_categories as $cat) {
 	if ($cat->parent == 0) {
 		$data['categorys'][$cat->cat_ID]=$cat->name;
 		$simular_sub_cats_calculator=1;
 	}
 	else{
 		if( array_key_exists($cat->name,$sc)){
	        $sc[$cat->name.'-'.$simular_sub_cats_calculator]=array('id'=>$cat->cat_ID,'pid'=>$cat->parent);
	    	$simular_sub_cats_calculator++;
   		}
   		else{
   			$sc[$cat->name]=array('id'=>$cat->cat_ID,'pid'=>$cat->parent);
   			$simular_sub_cats_calculator=1;
   		}
 	}
}
foreach ($sc as $k => $v) {
	if(array_key_exists($v['pid'],$data['categorys'])){
		return true;
	}
	else{
	  $for_search[]=$v['pid'];
}
}
$args = array(
     'taxonomy'     => $taxonomy,
     'orderby'      => $orderby,
     'show_count'   => $show_count,
     'pad_counts'   => $pad_counts,
     'hierarchical' => $hierarchical,
     'title_li'     => $title,
     'hide_empty'   => $empty,
     'include' => $for_search
);
$all_categories = get_categories( $args );
foreach ($all_categories as $cat) {
	$data['categorys'][$cat->cat_ID]=$cat->name;
}
foreach ($data['categorys'] as $key => $value) {
	foreach ($sc as $k=>$v) {
		if($key==$v['pid']){
			if(array_key_exists(1,$data['categorys'][$v['pid']])){
			$data['categorys'][$v['pid']][1][$v['id']]=$k;
			}
			else{
			$data['categorys'][$v['pid']]=array($value,array());
			$data['categorys'][$v['pid']][1][$v['id']]=$k;
			}
		}
	}
}
	wp_send_json($data);
	wp_die();
	wp_reset_query();
}
add_action('wp_ajax_co','creata_order');
add_action('wp_ajax_nopriv_co','creata_order');
function creata_order(){
	check_ajax_referer( 'Mirin' , 'nonce' , true );
	$address = array(
            'first_name' => $_POST['client']['name'],
            'last_name'  => $_POST['client']['tel'],
            'phone'      => (int)$_POST['client']['tel'],
            'address_1'  => '00 Some Some',
            'address_2'  => '11 Some Some', 
            'city'       => 'Some',
            'state'      => 'TN',
            'postcode'   => '12345',
            'country'    => 'IN'
        );

        $order = wc_create_order();
        foreach ( $_POST['ordered_goods'] as $key => $value) {
        	$order->add_product( get_product($value['id']), $value['count'] ); //(get_product with id and next is for quantity)
        }
        $order->set_address( $address, 'billing' );
        $total=$order->calculate_totals();
	wp_send_json($total);
	wp_die();
}
