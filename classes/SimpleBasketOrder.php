<?php
/**
 * Класс корзины заказа
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

	/* ------------------- Определения класса  ---------------------- */	
	const ITEMS			= 'SIMPLE_BASKET_ITEMS';
	const TITLE			= 'SIMPLE_BASKET_TITLE';
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
	 public $items;


	/**
	 * Конструктор класса
	 */
	 private function __construct()
	 {
		$this->items = (isset($_SESSION[self::ITEMS])) ? $_SESSION[self::ITEMS] : array();
		$this->userName = (isset($_SESSION[self::USER_NAME])) ? $_SESSION[self::USER_NAME] : '';
		$this->userEmail = (isset($_SESSION[self::USER_EMAIL])) ? $_SESSION[self::USER_EMAIL] : '';
		$this->userPhone = (isset($_SESSION[self::USER_PHONE])) ? $_SESSION[self::USER_PHONE] : '';
		$this->userComment = (isset($_SESSION[self::USER_COMMENT])) ? $_SESSION[self::USER_COMMENT] : '';
		$this->errorMessages = array();
	 }

	/**
	 * Деструктор класса
	 */
	 public function __destruct()
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
	 * Сообщение об ошибке
	 * @var string
	 */
	 public $errorMessages;

	/**
	 * Сохраняет данные пользователя
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
	 * Проверка что в корзине есть товары
	 * @return bool true - товаров нет
	 */
	public function isEmpty()
	{
		return (count($this->items) == 0);
	}


	/**
	 * Проверка к готовности заказа
	 * @return bool true - заказ можно оформлять
	 */
	public function isValid()
	{
		$this->errorMessages = array();
		
		// Заполнение полей
		if (empty($this->userName) || empty($this->userEmail) || empty($this->userPhone))
			$this->errorMessages[] = __('Please enter required data!', 'simple_basket');

		// Правильность E-mail
		if (!preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/', $this->userEmail))
			$this->errorMessages[] = __('Please specify correct E-mail!', 'simple_basket');

		// Правильность телефона
		if (!preg_match('/^\+?[ \-\(\)0-9]{11,20}$/', $this->userPhone))
			$this->errorMessages[] = __('Please specify correct phone!', 'simple_basket');
			
		// В корзине есть товары
		if ($this->isEmpty())
			$this->errorMessages[] = __('Basket is empty.', 'simple_basket');

		return (count($this->errorMessages) == 0);
	}	



	/* ------------------- Работа с корзиной  ---------------------- */
	/**
	 * Добавляет в заказ очередной элемент
	 * @param string id Код продукта
	 * @param string title наименование товара
	 * @param float price цена товара
	 * @param string category категория товара
	 */
	 public function add($id, $title, $price, $category='')
	 {
		
		// Нормализуем цену
		$price = (float) preg_replace(
			// patterns
			array(
				'/' . addslashes(__('USD', 'simple_basket')) . '/',
				'/[\s]/',
				'/,/'
			),
			// replacements
			array(
				'',
				'',
				'.'
			),
			$price
		);
		
		// Если такой элемент уже есть...
		if (array_key_exists($id, $this->items))
		{			
			// Увеличим количество
			$this->items[$id][self::QUO]++;	
		}
		else
		{
			// Добавим элемент
			$this->items[$id] = array
			(
				self::TITLE => $title,
				self::QUO	=> 1,
				self::PRICE => $price,
				self::CATEGORY => $category
			);			
		}

	 }

	/**
	 * Обновляем в заказе количество элементов
	 * @param string id Код продукта
	 * @param int quo Число товаров
	 */
	 public function update($id, $quo)
	 {
		
		// Если такой элемент уже есть...
		if (array_key_exists($id, $this->items))
		{			
			if ($quo == 0)
			{
				// Удаляем элемент
				unset($this->items[$id]);
			}
			else
			{
				// Изменяем количество
				$this->items[$id][self::QUO] = $quo;				
			}
		}
	 }

	/**
	 * Очистка заказа
	 */
	 public function clear()
	 {
		 $this->items = array();
		 $this->userComment = '';
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
				'<td>' . __('Quo', 'simple_basket') . '</td>' .
				'<td>' . __('Price', 'simple_basket') . '</td>' .
				'<td>' . __('Summ', 'simple_basket') . '</td>' .
			'</tr></thead>' . 
			'<tbody>';
		$total = 0;
		foreach ($this->items as $id=>$item)
		{
			// Если в корзине количество товара меньше или ноль - пропускаем
			if ($item[self::QUO] <= 0) continue;
			$summ = $item[self::QUO] * $item[self::PRICE];
			/* translators: please replace USD by your country currency */
			$output .= '<tr data-product-id="' . $id . '"><td class="title">' . $item[self::TITLE] . '</td>' .
				'<td class="quo" data-value="' . $item[self::QUO] . '" data-id="' . $id . '">' . $item[self::QUO] . '</td>' .
				'<td class="price" data-value="' . $item[self::PRICE] . '">' . number_format($item[self::PRICE], 2, ',', ' ') . __('USD', 'simple_basket') . '</td>' . 
				'<td class="summ" data-value="' . $item[self::PRICE] . '">' . number_format($summ, 2, ',', ' ') . __('USD', 'simple_basket') . '</td></tr>'; 
			$total += $summ;
		}
		$output .= '</tbody><tfoot>' . 
						'<tr><td class="title">' . __('Total', 'simple_basket') . '</td>' .
						'<td class="summ"  colspan="3" data-value="' . $total . '">' . number_format($total, 2, ',', ' ') . __('USD', 'simple_basket') . '</td></tr>';
		$output .= '</tfoot></table><!--/.basket-->';
		return $output;
	 }

	/**
	 * Возвращает общую сумму заказа
	 * @return float сумма заказа
	 */
	 public function getTotal()
	 {
	 	$total = 0;
		foreach ($this->items as $id=>$item)
			$total += $item[self::QUO] * $item[self::PRICE];
		return $total;
	 }


}

?>