<?php

/*
	
@package sunsettheme
	
	========================
		THEME CUSTOM POST TYPES
	========================
*/


	add_action( 'init', 'tg_contact_custom_post_type_adv' );
	add_filter('manage_adv_posts_columns','sunset_set_contact_columns_adv');
	add_action('manage_adv_posts_custom_column','sunset_contact_custom_column_adv',10,2);

/* ADV */
function tg_contact_custom_post_type_adv() {
	$labels = array(
		'name' 				=> 'Điểm bán',
		'singular_name' 	=> 'Điểm bán',
		'menu_name'			=> 'Điểm bán',
		'name_admin_bar'	=> 'Điểm bán'
	);
	
	$args = array(
		'labels'			=> $labels,
		'show_ui'			=> true,
		'show_in_menu'		=> true,
		'capability_type'	=> 'post',
		'hierarchical'		=> false,
		'menu_position'		=> 25,
		'menu_icon'			=> 'dashicons-images-alt2',
		'supports'			=> array( 'title', 'thumbnail' , 'excerpt','editor' ),
	);

	register_taxonomy(
		'adv_category',
		'adv',
		array(
			'label' => __( 'Chuyên mục' ),
			'rewrite' => array( 'slug' => 'category_adv' ),
			'hierarchical' => true,
		)
	);

	register_post_type( 'adv', $args );
}

function sunset_set_contact_columns_adv($columns){
	$newColumns = array();
	$newColums['title'] = 'Tiêu đề';
	$newColums['author'] = 'Tác giả';
	return $newColums;
}

function sunset_contact_custom_column_adv($column,$post_id){
	switch ($column) {
		case 'title':
			echo get_the_title();
		case 'author':
			echo get_the_author();
		break;
	}
}

