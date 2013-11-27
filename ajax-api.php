<?php
/**
 * AJAX API корзины
 */

$simpeBasketAPI = new SimpleBasketAPI();


class SimpleBasketAPI
{
	/**
	 * Корзина
	 * @var SimpleBasketOrder
	 */
	 private $basket;	



	/**
	 * Конструктор класса
	 */
	public function __construct()
	{
		if ( is_admin() ) 
		{
			add_action( 'wp_ajax_nopriv_getTime', array( &$this, 'getTime'));
			add_action( 'wp_ajax_getTime', array( &$this, 'getTime'));

			add_action( 'wp_ajax_nopriv_getData', array( &$this, 'getData'));
			add_action( 'wp_ajax_getData', array( &$this, 'getData'));

			add_action( 'wp_ajax_nopriv_add', array( &$this, 'add'));
			add_action( 'wp_ajax_add', array( &$this, 'add'));



		}
		add_action( 'init', array( &$this, 'init' ) );


		$this->basket = SimpleBasketOrder::create();
	}

	/**
	 * Функция инициализации AJAX
	 */
	public function init()
	{
		wp_enqueue_script('simple-basket', plugin_dir_url( __FILE__ ) . 'js/simple-basket.min.js', array( 'jquery' ) );
		wp_localize_script('simple-basket', 'SimpleBasket', array(
		    'ajaxurl' => admin_url( 'admin-ajax.php' ),
		    'nonce' => wp_create_nonce( 'ajax-example-nonce' )
		) );
	}

	/**
	 * Функция проверки токена
	 */
	public function validateNonce()
	{
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'ajax-example-nonce' ) )
			die ( 'Invalid Nonce' );
	}

	/**
	 * Функция ответа
	 */
	public function responce($result)
	{
		header('Content-Type: application/json');
		echo json_encode($result);
		exit;
	}
	
	/* --------------------- AJAX методы ---------------------- */
	public function getTime()
	{
		$this->validateNonce();
		$this->responce(array(
			'time' => date('d.m.Y H:i:s')
		));
	}

	// Возврат корзины
	public function getData()
	{
		$this->validateNonce();
		$this->responce($this->basket);
	}

	// Добавление товара в корзину
	public function add()
	{
		$this->validateNonce();
		if (!isset($_REQUEST['id']))
			die ( 'ID not specified' );

			// Код товара
			$id = (int) $_REQUEST['id'];
			$product = get_post($id);
			$title = $product->post_title;
			// Цена
			$price = simple_basket_custom_fields($id, get_option('simple_basket_catalog_price'));
			// Вычисляем категорию по таксономии 
			$category = '';
			// Тип записи каталога товара
			$postType = $product->post_type;
			// Таксономии записи
			$taxonomies = get_object_taxonomies($postType);
			// Ищем таксономию, которая не тег
			foreach ($taxonomies as $taxonomy)
			{
				if (strpos($taxonomy, 'tag') !== FALSE) continue;
				// Берем элементы этой таксономии
				$categories = get_the_terms($id, $taxonomy);
				$category = (count($categories) > 0) ? $categories[0]->name : '';
				// Следующие таксономии не рассматриваем
				break;
			}
			
			// Добавляем в корзину
			if (!empty($title)) $this->basket->add($id, $title, $price, $category);
		$this->responce($this->basket);
	}
}
