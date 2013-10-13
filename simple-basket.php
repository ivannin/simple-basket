<?php
/*
Plugin Name: Simple basket
Plugin URI: https://github.com/ivannin/simple-basket
Description: Простая корзина для Wordpress
Version: 0.3
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
	// Старт сессии
	@session_start();
	
	// Локализация
	load_plugin_textdomain( 'simple_basket', false, basename(dirname(__FILE__)) . '/lang/' );

	// Режим и типы доставки
	if (get_option('simple_basket_delivery') == '1')
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

// ----------------------- GET параметры -----------------------
define('SIMPLE_BASKET_ADD',			'add');
define('SIMPLE_BASKET_UPDATE',		'update');
define('SIMPLE_BASKET_DELETE',		'delete');
define('SIMPLE_BASKET_CHECKOUT',	'checkout');
define('SIMPLE_BASKET_MODE',		'mode');

// ----------------------- Подключения -----------------------
require(plugin_dir_path(__FILE__) . 'order.php');
require(plugin_dir_path(__FILE__) . 'buy-now.php');
require(plugin_dir_path(__FILE__) . 'order-form.php');

// ------------------- Некоторые общие функии ----------------
// Возвращает значение произвольных полей
function simple_basket_custom_fields($postId, $customField) 
{
	$values = get_post_meta($postId, $customField);
	if (count($values) == 0) 
		return '';
	else
		return trim($values[0]);		
}


// Подключения CSS, JavaScript и т.п. 
add_action('wp_enqueue_scripts', 'simple_basket_externals');
function simple_basket_externals() 
{
    // Respects SSL, Style.css is relative to the current file
    wp_register_style('simple-basket', plugins_url('simple-basket.min.css', __FILE__) );
    wp_enqueue_style('simple-basket' );
}

// Для почты
function simple_basket_set_html_content_type()
{
	return 'text/html';
}

/**
* Вместо wp_redirect() используем свою функцию, так как
* скорее всего git добавил концы строк в файлы и wp_redirect() не работает (был начат вывод страницы).
* Разбираться лениво, поэтому делаю переадресацию скриптом JavaScript.
*/
function simple_basket_redirect($url)
{
	echo "<script>location.replace('$url');</script>";
	exit;

}
?>