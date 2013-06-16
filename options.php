<?php
// Параметры плагина
add_option('simple_basket_buynow_caption', __('Buy', 'simple_basket'));
add_option('simple_basket_catalog_id', __('product', 'simple_basket'));
add_option('simple_basket_catalog_price', __('Price', 'simple_basket'));
add_option('simple_basket_google_analytics_mode', '0');

// Принимаем данные
if (isset($_POST['buynowcaption']))
	update_option('simple_basket_buynow_caption', $_POST['buynowcaption']);
if (isset($_POST['catalogid']))
	update_option('simple_basket_catalog_id', $_POST['catalogid']);
if (isset($_POST['pricefield']))
	update_option('simple_basket_catalog_price', $_POST['pricefield']);
if (isset($_POST['googleanalyticsmode']))
	update_option('simple_basket_google_analytics_mode', $_POST['googleanalyticsmode']);

?>
<style type="text/css">
#simple_basket fieldset {
	border:  1px solid gray;
	border-radius: 4px;
	padding:  10px;
	margin-right:  10px;
	margin-bottom:  10px;	
}
#simple_basket legend {
	font-size: 14pt;
	padding: 5px;
}	
#simple_basket fieldset div {
	margin-bottom:  10px;
	clear:  both;
}
#simple_basket fieldset div:not(:last-child) {
	border-bottom: 1px dotted gray;
}
#simple_basket fieldset div p {
	margin-left:  160px;
}	
#simple_basket label {
	display:  block;
	float:  left;
	width:  150px;
	margin-right: 10px;
	padding-top: 4px;
	text-align: right;
	font-weight: bold;
}
#simple_basket input {
	width:  50%;		
		
}
</style>
<div id="simple_basket">
	<h2><?php 
		echo '<img src="' . plugins_url( 'img/basket-icon-32x32.png' , __FILE__ ) . '" > ';
		_e('Simple Basket', 'simple_basket');
	?></h2>
	<p><?php _e('Please change the settings with caution. It is better to go to a technical expert.', 'simple_basket')?></p>
	<form method="post" action="#">
		<fieldset>
			<legend><?php _e('Buy Now Button', 'simple_basket')?></legend>
			<div>
				<label for="buyNowCaption"><?php _e('Buy Now Button Caption', 'simple_basket')?></label>
				<input id="buyNowCaption" type="text" name="buynowcaption" value="<?php echo get_option('simple_basket_buynow_caption'); ?>" />
				<p><?php _e('This parameter specifies the caption on the buttom [Buy]. To display this button use shortcode [buy-now]', 'simple_basket')?></p>
			</div>
		</fieldset>
		<fieldset>
			<legend><?php _e('Product Catalog', 'simple_basket')?></legend>
			<div>
				<label for="catalogType"><?php _e('Catalog Post Type', 'simple_basket')?></label>
				<input id="catalogType" type="text" name="catalogid" value="<?php echo get_option('simple_basket_catalog_id'); ?>" />
				<p><?php _e('This parameter specifies product post type.', 'simple_basket')?></p>
			</div>
			<div>
				<label for="priceCustomFiled"><?php _e('Price Custom Field', 'simple_basket')?></label>
				<input id="priceCustomFiled" type="text" name="pricefield" value="<?php echo get_option('simple_basket_catalog_price'); ?>" />
				<p><?php _e('This parameter specifies the name of product custom field that contains the price.', 'simple_basket')?></p>
			</div>
		</fieldset>	
		<fieldset>
			<legend><?php _e('Google Analytics', 'simple_basket')?></legend>
			<div>
				<label for="googleAnalytics"><?php _e('Google Analytics intergation', 'simple_basket')?></label>
				<?php 
					$optNo = get_option('simple_basket_google_analytics_mode'); 
					function showSelected($no=0, $current=0)
					{
						if ($no == $current) echo ' selected="selected"';
					}
				?>
				<select id="googleAnalytics" name="googleanalyticsmode">
					<option value="0"<?php showSelected(0, $optNo)?>><?php _e('None', 'simple_basket')?></option>
					<option value="1"<?php showSelected(1, $optNo)?>><?php _e('Google Analytics (ga.js)', 'simple_basket')?></option>
					<option value="2"<?php showSelected(2, $optNo)?>><?php _e('Universal Analytics (analytics.js)', 'simple_basket')?></option>
				</select>
				<p><?php _e('This parameter specifies the integration mode with Google Analytits. This option adds the necessary tracking code to the page "Thanks for your purchase". For correct intergation e-commerce mode must be enabled at Google Analytics profile options.', 'simple_basket')?></p>
			</div>
		</fieldset>	

		<div>
			<button type="submit"><?php _e('Update settings', 'simple_basket')?></button>
		</div>
	</form>
</div>