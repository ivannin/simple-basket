<?php
/*
Plugin Name: Simple basket
Plugin URI: https://github.com/ivannin/simple-basket
Description: Простая корзина для Wordpress
Version: 0.1
Author: Иван Никитин
Author URI: http://ivannikitin.com
License:
	Copyright 2013  Ivan Nikitin  (email : ivan@nikitin.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
================================================================================
*/

// Страница параметров плагина
add_action( 'admin_menu', 'simpleBasketCreateAdminMenu' );
function simpleBasketCreateAdminMenu() {
	add_options_page(__('Simple Basket Options'), __('Simple Basket'), 
		'manage_options', 'simple-baslet', 'simpleBasketOptions' );
}

function simpleBasketOptions() 
{
	if (!current_user_can( 'manage_options' ))
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	
	include(plugin_dir_path(__FILE__).'options.php');
}


?>
