<?php
/**
 * Форма заказа
 */

// ---------------------- Регистрация типа данных ----------------------
define('SIMPLE_BASKET_ORDER_TYPE', 'simple_basket_order');
add_action( 'init', 'createOrderPost', 0);
function createOrderPost() 
{
	$labels = array(
		'name'                => __( 'Orders', 'simple_basket' ),
		'singular_name'       => __( 'Order', 'simple_basket' ),
		'menu_name'           => __( 'Orders', 'simple_basket' ),
		'all_items'           => __( 'All orders', 'simple_basket' ),
		'view_item'           => __( 'View orders', 'simple_basket' ),
		'add_new_item'        => __( 'Add new order', 'simple_basket' ),
		'add_new'             => __( 'New order', 'simple_basket' ),
		'edit_item'           => __( 'Edit order', 'simple_basket' ),
		'update_item'         => __( 'Update order', 'simple_basket' ),
		'search_items'        => __( 'Search order', 'simple_basket' ),
		'not_found'           => __( 'No orders found', 'simple_basket' ),
		'not_found_in_trash'  => __( 'No orders found in Trash', 'simple_basket' ),
	);

	$args = array(
		'label'               => __( 'Order', 'simple_basket' ),
		'description'         => __( 'Orders', 'simple_basket' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'author', 'custom-fields', 'editor'),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => plugins_url('/img/order-icon16x16.png', __FILE__ ),
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite'             => false,
		'capability_type'     => 'post',
	);

	register_post_type(SIMPLE_BASKET_ORDER_TYPE, $args);
}

// Иконка на странице аминистрирования
add_action('admin_head', 'simple_basket_admin_order_css');
function simple_basket_admin_order_css() 
{ ?>
<style type="text/css">
	.icon32-posts-simple_basket_order {
		background: url('/wp-content/plugins/simple-basket/img/order-icon32x32.png') no-repeat !important;
	}
</style>
<?php
}

// Register Status Taxonomy
define('SIMPLE_BASKET_ORDER_STATUS', 'simple_basket_status');
add_action( 'init', 'createOrderTaxonomy', 0 );
function createOrderTaxonomy()  
{
	$labels = array(
		'name'                       => __( 'Statuses', 'simple_basket' ),
		'singular_name'              => __( 'Status', 'simple_basket' ),
		'menu_name'                  => __( 'Status', 'simple_basket' ),
		'all_items'                  => __( 'All Statuses', 'simple_basket' ),
		'parent_item'                => __( 'Parent Status', 'simple_basket' ),
		'parent_item_colon'          => __( 'Parent Status:', 'simple_basket' ),
		'new_item_name'              => __( 'New Status Name', 'simple_basket' ),
		'add_new_item'               => __( 'Add New Status', 'simple_basket' ),
		'edit_item'                  => __( 'Edit Status', 'simple_basket' ),
		'update_item'                => __( 'Update Status', 'simple_basket' ),
		'separate_items_with_commas' => __( 'Separate statuses with commas', 'simple_basket' ),
		'search_items'               => __( 'Search statuses', 'simple_basket' ),
		'add_or_remove_items'        => __( 'Add or remove statuses', 'simple_basket' ),
		'choose_from_most_used'      => __( 'Choose from the most used statuses', 'simple_basket' ),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => false,
	);
	register_taxonomy(SIMPLE_BASKET_ORDER_STATUS, SIMPLE_BASKET_ORDER_TYPE, $args);

	// Статусы по уумолчанию
	$defaultStatuses = array(
		'new'			=> array('title' => __('New', 'simple_basket'), 'description' => __('New Order', 'simple_basket')),
		'in_process'	=> array('title' => __('In Process', 'simple_basket'), 'description' => __('Order In Process', 'simple_basket')),
		'completed'		=> array('title' => __('Completed', 'simple_basket'), 'description' => __('Order Completed', 'simple_basket')),
		'canceled'		=> array('title' => __('Canceled', 'simple_basket'), 'description' => __('Order Canceled', 'simple_basket')),
	);
	foreach ($defaultStatuses as $slug => $status)
	{
		if (!term_exists($status['title'], SIMPLE_BASKET_ORDER_STATUS)) 
		{
			wp_insert_term(
				$status['title'],				// the term 
				SIMPLE_BASKET_ORDER_STATUS,		// the taxonomy
				array(
					'description'	=> $status['description'],
					'slug'			=> $slug,
					'parent'		=> 0
				));
		}
	}
}

// Дополнительные колонки в таблице заказов
define('SIMPLE_BASKET_COLUMN_ORDER_SUMM', 'colOrderSumm');

add_filter('manage_' . SIMPLE_BASKET_ORDER_TYPE . '_posts_columns', 'getOrderColumnsHead');  
add_action('manage_' . SIMPLE_BASKET_ORDER_TYPE . '_posts_custom_column', 'showOrderColumnsContent', 10, 2); 

// Названия колонок в таблице заказов  
function getOrderColumnsHead($defaults) 
{
	// Изменяем существующие колонки
	$defaults['title'] = __('Order Code', 'simple_basket');
	$defaults['author'] = __('Customer', 'simple_basket');

	// Добавляем новые колонки  
    $defaults[SIMPLE_BASKET_COLUMN_ORDER_SUMM] = __('Summ', 'simple_basket');

    return $defaults;  
}  
  
// Вывод данных в таблице заказов  
function showOrderColumnsContent($column_name, $postId) 
{  
    switch ($column_name)
	{
		case SIMPLE_BASKET_COLUMN_ORDER_SUMM:
			echo simple_basket_custom_fields($postId, __('Summ', 'simple_basket')), ' ', 
				/* translators: please replace USD by your country currency */
				__('USD', 'simple_basket');
			break;
	}
}

// Свойства заказа (произвольные поля) по умолчанию при ручном добавлении
add_action('wp_insert_post', 'setOrderDefaults');
function setOrderDefaults($postId)
{
    if (isset($_GET['post_type']) && $_GET['post_type'] == SIMPLE_BASKET_ORDER_TYPE) 
	{
		add_post_meta($postId, __('Summ', 'simple_basket'), '0', true);
	}
    return true;
}

?>