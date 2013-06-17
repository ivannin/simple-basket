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

// ------------------------- Инициализация -------------------------
add_action('plugins_loaded', 'simple_basketInit');
function simple_basketInit() 
{
	// Локализация
	load_plugin_textdomain( 'simple_basket', false, basename(dirname(__FILE__)) . '/lang/' );

	// Типы доставки
	include(plugin_dir_path(__FILE__).'delivery.php');
}



// ---------------- Страница параметров плагина ----------------
add_action( 'admin_menu', 'simple_basketCreateAdminMenu' );
function simple_basketCreateAdminMenu() {
	add_options_page(__('Simple Basket Options', 'simple_basket'), __('Simple Basket', 'simple_basket'), 
		'manage_options', 'simple-baslet', 'simple_basketOptions' );
}

function simple_basketOptions() 
{
	if (!current_user_can( 'manage_options' ))
		wp_die( __( 'You do not have sufficient permissions to access this page.','simple_basket') );
	
	include(plugin_dir_path(__FILE__).'options.php');
}







?>
