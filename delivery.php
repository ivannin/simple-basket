<?php
define('SIMPLE_BASKET_DELIVERY', 'delivery');

// Hook into the 'init' action
add_action( 'init', 'createDeliveryPostType', 0 );

// Register Custom Post Type
function createDeliveryPostType() 
{
	$labels = array(
		'name'                => __( 'Delivery Types', 'simple_basket' ),
		'singular_name'       => __( 'Delivery Type', 'simple_basket' ),
		'menu_name'           => __( 'Delivery', 'simple_basket' ),
		'parent_item_colon'   => __( 'Parent Delivery Type:', 'simple_basket' ),
		'all_items'           => __( 'All Delivery Types', 'simple_basket' ),
		'view_item'           => __( 'View Delivery Types', 'simple_basket' ),
		'add_new_item'        => __( 'Add New Delivery Type', 'simple_basket' ),
		'add_new'             => __( 'New Delivery Type', 'simple_basket' ),
		'edit_item'           => __( 'Edit Delivery Type', 'simple_basket' ),
		'update_item'         => __( 'Update Delivery Type', 'simple_basket' ),
		'search_items'        => __( 'Search delivery type', 'simple_basket' ),
		'not_found'           => __( 'No delivery types found', 'simple_basket' ),
		'not_found_in_trash'  => __( 'No delivery types found in Trash', 'simple_basket' ),
	);

	$args = array(
		'label'               => __('delivery', 'simple_basket'),
		'description'         => __('Delivery types', 'simple_basket'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => plugins_url('/img/delivery16x16.png', __FILE__ ),
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);

	register_post_type(SIMPLE_BASKET_DELIVERY, $args);
}

// Иконка на странице аминистрирования
add_action('admin_head', 'simple_basket_admin_delivery_css');
function simple_basket_admin_delivery_css() 
{ ?>
<style type="text/css">
	.icon32-posts-delivery {
		background: url('/wp-content/plugins/simple-basket/img/delivery32x32.png') no-repeat !important;
	}
</style>
<?php
}

// Дополнительные колонки в таблице доставки
define('SIMPLE_BASKET_COLUMN_DELIVERY_COST', 'colProductPrice');

add_filter('manage_delivery_posts_columns', 'getDeliveryColumnsHead');  
add_action('manage_delivery_posts_custom_column', 'showDeliveryColumnsContent', 10, 2); 

// Названия колонок в таблице доставки  
function getDeliveryColumnsHead($defaults) 
{
	// Добавляем новые колонки  
    $defaults[SIMPLE_BASKET_COLUMN_DELIVERY_COST] = __('Cost', 'simple_basket');

	// Убираем лишнее
	unset($defaults['date']);
    return $defaults;  
}  
  
// Вывод данных в таблице доставки  
function showDeliveryColumnsContent($column_name, $postId) 
{  
    switch ($column_name)
	{
		case SIMPLE_BASKET_COLUMN_DELIVERY_COST:
			echo simple_basket_custom_fields($postId, __('Cost', 'simple_basket')), ' ', 
				/* translators: please replace USD by your country currency */
				__('USD', 'simple_basket');
			break;

	}
}

// Свойства доставки (произвольные поля) по умолчанию
add_action('wp_insert_post', 'setDeliveryDefaults');
function setDeliveryDefaults($postId)
{
    if ($_GET['post_type'] == SIMPLE_BASKET_DELIVERY) 
	{
		add_post_meta($postId, __('Cost', 'simple_basket'), '0', true) 
			or update_post_meta($postId, __('Cost', 'simple_basket'), '0');
	}
    return true;
}


?>