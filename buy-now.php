<?php
/**
 * Шорткод и функции кнопки купить
 */
add_shortcode('buy-now', 'getBuyNowButton');
function showBuyNowButton()
{
	echo getBuyNowButton(array());
}
function getBuyNowButton($atts)
{
	$id = get_the_ID();
	$caption = get_option('simple_basket_buynow_caption');
	$title = get_the_title();
	$url = get_option('simple_basket_order_page');
	if (strpos($url, '?') !== FALSE)
		$url .= '&' . SIMPLE_BASKET_ADD . '=' . $id;
	else
		$url .= '?' . SIMPLE_BASKET_ADD . '=' . $id;
	switch (get_option('simple_basket_google_analytics_mode'))
	{
		case 1:
			$ga = ' onclick="_gaq.push([\'_trackEvent\', \'' . __('Basket', 'simple_basket') .  '\', \'' . __('Add to basket', 'simple_basket')  . '\', \'' . $title . '\']);"';
			break;
		case 2:
			$ga = ' onclick="ga(\'send\', \'event\', \'' . __('Basket', 'simple_basket') .  '\', \'' . __('Add to basket', 'simple_basket')  . '\', \'' . $title . '\');"';
			break;
		default:
			$ga = '';
	}
	$output = '<a href="' . $url . '" class="simple-basket-buy-now"' . $ga . '>' . 	$caption .'</a>';
	return $output;
}
?>
