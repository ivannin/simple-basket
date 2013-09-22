<?php
// Параметры плагина
add_option('simple_basket_buynow_caption', __('Buy', 'simple_basket'));
add_option('simple_basket_order_page', '');
add_option('simple_basket_catalog_price', __('Price', 'simple_basket'));
add_option('simple_basket_delivery', '0');
add_option('simple_basket_delivery_default', '0');
add_option('simple_basket_google_analytics_mode', '0');
add_option('simple_basket_conformation_email_post', '');
add_option('simple_basket_admin_email_post', '');


// Принимаем данные
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (isset($_POST['buynowcaption']))
		update_option('simple_basket_buynow_caption', $_POST['buynowcaption']);

	if (isset($_POST['orderpage']))
		update_option('simple_basket_order_page', $_POST['orderpage']);

	if (isset($_POST['pricefield']))
		update_option('simple_basket_catalog_price', $_POST['pricefield']);

	if (isset($_POST['deliverymode']))
		update_option('simple_basket_delivery', '1');
	else
		update_option('simple_basket_delivery', '0');

	if (isset($_POST['deliveryplan']))
		update_option('simple_basket_delivery_default', $_POST['deliveryplan']);

	if (isset($_POST['googleanalyticsmode']))
		update_option('simple_basket_google_analytics_mode', $_POST['googleanalyticsmode']);

	if (isset($_POST['confirmemail']))
		update_option('simple_basket_conformation_email_post', $_POST['confirmemail']);

	if (isset($_POST['adminemail']))
		update_option('simple_basket_admin_email_post', $_POST['adminemail']);
}


// Для списка SELECT
function showSelected($current=0, $setting=0)
{
	if ($setting == $current) echo ' selected="selected"';
}


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
#simple_basket fieldset div p, #simple_basket fieldset div li {
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
#simple_basket input[type="checkbox"] {
	width:  20px;			
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
			<legend><?php _e('Basket Options', 'simple_basket')?></legend>
			<div>
				<label for="buyNowCaption"><?php _e('Buy Now Button Caption', 'simple_basket')?></label>
				<input id="buyNowCaption" type="text" name="buynowcaption" value="<?php echo get_option('simple_basket_buynow_caption'); ?>" />
				<p><?php _e('This parameter specifies the caption on the buttom [Buy]. To display this button use shortcode [buy-now] or call function &lt;?php showBuyNowButton() ?&gt;', 'simple_basket')?></p>
			</div>
			<div>
				<label for="orderPage"><?php _e('Order Page', 'simple_basket')?></label>
				<input id="orderPage" type="text" name="orderpage" value="<?php echo get_option('simple_basket_order_page'); ?>" />
				<p><?php _e('This parameter specifies the URL of page contains order form. To display thу order form use shortcode [order-form]', 'simple_basket')?></p>
			</div>
		</fieldset>
		<fieldset>
			<legend><?php _e('Product Catalog', 'simple_basket')?></legend>
			<div>
				<label for="priceCustomFiled"><?php _e('Price Custom Field', 'simple_basket')?></label>
				<input id="priceCustomFiled" type="text" name="pricefield" value="<?php echo get_option('simple_basket_catalog_price'); ?>" />
				<p><?php _e('This parameter specifies the name of product custom field that contains the price.', 'simple_basket')?></p>
			</div>
		</fieldset>	
		<fieldset>
			<legend><?php _e('Delivery', 'simple_basket')?></legend>
			<div>
				<label for="deliveryMode"><?php _e('Paid Delivery', 'simple_basket')?></label>
				<input id="deliveryMode" type="checkbox" name="deliverymode" value="1"<?php
					if (get_option('simple_basket_delivery') == '1')
						echo ' checked="checked"'?> />
				<span><?php _e('My shop has different paid delivery plans.', 'simple_basket')?></span>
				<p><?php _e('After change this mode you must update settings at <a href="/wp-admin/options-permalink.php">permalink options page</a>.', 'simple_basket')?></p>
			</div>
			<div>
				<label for="deliveryPlan"><?php _e('Default Delivery Plan', 'simple_basket')?></label>
				<select id="deliveryPlan" name="deliveryplan">
					<?php $defaultDeliveryPlan = get_option('simple_basket_delivery_default'); ?>
					<option value="0"<?php showSelected(0, $defaultDeliveryPlan)?>><?php _e('None', 'simple_basket')?></option>
					<?php 
						$args = array('post_type' => 'delivery');
						$deliveryType = new WP_Query($args);
						while ($deliveryType->have_posts())
						{
							$deliveryType->the_post(); ?>
					<option value="<?php echo $deliveryType->post->ID ?>"<?php showSelected($deliveryType->post->ID, $defaultDeliveryPlan)?>><?php echo get_the_title($deliveryType->post->ID) ?></option>
					<?php
						}
					?>
				</select>
				<p><?php _e('This parameter specifies the default delivery plan at customer order.', 'simple_basket')?></p>
			</div>
		</fieldset>	

		<fieldset>
			<legend><?php _e('Google Analytics', 'simple_basket')?></legend>
			<div>
				<label for="googleAnalytics"><?php _e('Google Analytics intergation', 'simple_basket')?></label>
				<?php 
					$gaMode = get_option('simple_basket_google_analytics_mode'); 
				?>
				<select id="googleAnalytics" name="googleanalyticsmode">
					<option value="0"<?php showSelected(0, $gaMode)?>><?php _e('None', 'simple_basket')?></option>
					<option value="1"<?php showSelected(1, $gaMode)?>><?php _e('Google Analytics (ga.js)', 'simple_basket')?></option>
					<option value="2"<?php showSelected(2, $gaMode)?>><?php _e('Universal Analytics (analytics.js)', 'simple_basket')?></option>
				</select>
				<p><?php _e('This parameter specifies the integration mode with Google Analytits. This option adds the necessary tracking code to the page "Thanks for your purchase". For correct intergation e-commerce mode must be enabled at Google Analytics profile options.', 'simple_basket')?></p>
			</div>
		</fieldset>	

		<fieldset>
			<legend><?php _e('Confirmation E-mail', 'simple_basket')?></legend>
			<div>
				<label for="confirmationEmail"><?php _e('User E-mail Post Label', 'simple_basket')?></label>
				<input id="confirmationEmail" type="text" name="confirmemail" value="<?php echo get_option('simple_basket_conformation_email_post'); ?>" />
				<p>&nbsp;</p>
			</div>
			<div>
				<label for="adminEmail"><?php _e('Admin E-mail Post Label', 'simple_basket')?></label>
				<input id="adminEmail" type="text" name="adminemail" value="<?php echo get_option('simple_basket_admin_email_post'); ?>" />
				<p>&nbsp;</p>			
			</div>
			<div>
				<?php _e('<p>This parameters specify the label of post with user confirmation e-mail text.<br> ' .  
							'Create the new post, set the status DRAFT and specify the post label at this field.<br/> ' .  
							'If this paramater is empty no e-mail will send to user.<br/> '.
							'You may user the following short codes:</p><ul> ' . 
							'<li>[order-code] - the order code</li>' .
							'<li>[order-customer] - the customer name </li>' .
							'<li>[order-email] - the customer E-mail </li>' .
							'<li>[order-phone] - the customer phone number </li>' .
							'<li>[order-comment] - the order comment </li>' .
							'<li>[order-items] - the table with order items</li>' .
							'</ul>', 'simple_basket')?>

			</div>
		</fieldset>	

		<div>
			<button class="button button-primary" type="submit"><?php _e('Update settings', 'simple_basket')?></button>
		</div>
	</form>
</div>