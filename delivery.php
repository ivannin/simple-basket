<?php
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
		'label'               => __( 'delivery', 'simple_basket' ),
		'description'         => __( 'Delivery types', 'simple_basket' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', ),
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

	register_post_type( 'delivery', $args );
}

// Иконка на странице аминистрирования
add_action( 'admin_head', 'simple_basket_admin_css' );
function simple_basket_admin_css() 
{ ?>
<style type="text/css">
	.icon32-posts-delivery {
		background: url('/wp-content/plugins/simple-basket/img/delivery32x32.png') no-repeat !important;
	}
</style>
<?php
}
?>