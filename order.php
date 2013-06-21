<?php
/**
 * Класс обработки заказа
 *
 */

class SimpleBasketOrder
{
	/* ----------------------- Класс-синглтон ------------------------- */
	/**
	 * Экземпляр класса
	 * @static
	 */
	 private static $instance = NULL;

	/**
	 * Возвращает экземпляр класса
	 * @static
	 * @return SimpleBasketOrder
	 */
	 public static function create()
	 {
	 	if (!self::$instance)
			self::$instance = new SimpleBasketOrder();
		return self::$instance;
	 }

	/**
	 * Добавляет в заказ очередной элемент
	 * @param string title наименование товара
	 * @param float price цена товара
	 * @param string category категория товара
	 * @static
	 */
	 public static function addItem($title, $price, $category='')
	 {
		$order = self::create();
		$order->add($title, $price, $category);
	 }


	/* ------------------- Определения класса  ---------------------- */	
	const ITEMS			= 'SIMPLE_BASKET_ITEMS';
	const QUO			= 'SIMPLE_BASKET_QUO';
	const PRICE			= 'SIMPLE_BASKET_PRICE';
	const CATEGORY		= 'SIMPLE_BASKET_CATEGORY';
	const USER_EMAIL	= 'SIMPLE_BASKET_USER_EMAIL';
	const USER_PHONE	= 'SIMPLE_BASKET_USER_PHONE';
	const USER_NAME		= 'SIMPLE_BASKET_USER_NAME';
	const USER_COMMENT	= 'SIMPLE_BASKET_USER_COMMENT';

	/* ------------------- Конструктор и сессия  ---------------------- */
	/**
	 * Элементы заказа
	 * @var mixed
	 */
	 private $items;


	/**
	 * Конструктор класса
	 */
	 private function __construct()
	 {
	 	// Гарантированно начнем сессию
		session_start();
		$this->items = (isset($_SESSION[self::ITEMS])) ? $_SESSION[self::ITEMS] : array();
		$this->userName = (isset($_SESSION[self::USER_NAME])) ? $_SESSION[self::USER_NAME] : '';
		$this->userEmail = (isset($_SESSION[self::USER_EMAIL])) ? $_SESSION[self::USER_EMAIL] : '';
		$this->userPhone = (isset($_SESSION[self::USER_PHONE])) ? $_SESSION[self::USER_PHONE] : '';
		$this->userComment = (isset($_SESSION[self::USER_COMMENT])) ? $_SESSION[self::USER_COMMENT] : '';
	 }

	/**
	 * Деструктор класса
	 */
	 private function __destruct()
	 {
		$_SESSION[self::ITEMS] = $this->items;
		$_SESSION[self::USER_NAME] = $this->userName;
		$_SESSION[self::USER_EMAIL] = $this->userEmail;
		$_SESSION[self::USER_PHONE] = $this->userPhone;
		$_SESSION[self::USER_COMMENT] = $this->userComment;
	 }
	/* ------------------- Данные пользователя  ---------------------- */
	/**
	 * Имя пользователя
	 * @var string
	 */
	 public $userName;	
	/**
	 * E-mail пользователя
	 * @var string
	 */
	 public $userEmail;	
	/**
	 * Телефон пользователя
	 * @var string
	 */
	 public $userPhone;	
	/**
	 * Комментарий пользователя
	 * @var string
	 */
	 public $userComment;

	/**
	 * Схраняет данные пользователя
	 * @param string name наименование товара
	 * @param string email наименование товара
	 * @param string phone наименование товара
	 * @param string comment наименование товара
	 */
	 public function setUserData($name, $email, $phone, $comment='')
	 {
		$this->userName = $name;
		$this->userEmail = $email;
		$this->userPhone = $phone;
		if (!empty($comment)) $this->userComment = $comment;
	 }

	/**
	 * Очистка заказа
	 */
	 public function clear()
	 {
		 $this->items = array();
		 $this->userComment = '';
	 }

	/* ------------------- Работа с корзиной  ---------------------- */
	/**
	 * Добавляет в заказ очередной элемент
	 * @param string title наименование товара
	 * @param float price цена товара
	 * @param string category категория товара
	 */
	 public function add($title, $price, $category='')
	 {
		// Если такой элемент уже есть...
		if (array_key_exists($this->items, $title))
		{
			// Увеличим количество
			$this->items[$title][self::QUO]++;			
			return;
		}
		// Добавим элемент
		$this->items[$title] = array
		(
			self::QUO	=> 1,
			self::PRICE => $price,
			self::CATEGORY => $category
		);
	 }
	/**
	 * Возвращает HTML код корзины
	 * @return string HTML код корзины
	 */
	 public function getHTML()
	 {
		$output = '<table class="basket">' .
			'<thead><tr>' .
				'<tr><td>' . __('Title', 'simple_basket') . '</td>' .
				'<tr><td>' . __('Quo', 'simple_basket') . '</td>' .
				'<tr><td>' . __('Price', 'simple_basket') . '</td>' .
				'<tr><td>' . __('Summ', 'simple_basket') . '</td>' .
			'</tr></thead>' . 
			'<tbody>';
		$total = 0;
		foreach ($this->$items as $title=>$item)
		{
			// Если в корзине количество товара меньше или ноль - пропускаем
			if ($item[self::QUO] <= 0) continue;
			$summ = $item[self::QUO] * $item[self::PRICE];
			$output .= '<tr><td class="title">' . $title . '</td>' .
				'<td class="quo" data-value="' . $item[self::QUO] . '">' . $item[self::QUO] . '</td>' .
				'<td class="price" data-value="' . $item[self::PRICE] . '">' . number_format($item[self::PRICE], 2, ',', ' ') . '</td>' . 
				'<td class="summ" data-value="' . $item[self::PRICE] . '">' . number_format($summ, 2, ',', ' ') . '</td></tr>'; 
			$total += $summ;
		}
		$output .= '</tbody><tfoot>' . 
						'<tr><td class="title" rowspan="3">' . __('Total', 'simple_basket') . '</td>' .
						'<td class="summ" data-value="' . $total . '">' . number_format($total, 2, ',', ' ') . '</td></tr>';
		$output .= '</tfoot></table><!--/.basket-->';
	 }




}

?>
