/**
 * JS API корзины
 */

jQuery(function ($)
{

	// Демо-метод вывода времени с сервера
	SimpleBasket.getTime = function (callback)
	{
		jQuery.ajax({
			url: SimpleBasket.ajaxurl,
			type: 'POST',
			data: ({
				action: 'getTime',
				nonce: SimpleBasket.nonce
			}),
			success: function (data)
			{
				if (callback) callback(data);
			}
		});
	}

	// Метод выводит содержимое корзины
	SimpleBasket.getData = function (callback)
	{
		jQuery.ajax({
			url: SimpleBasket.ajaxurl,
			type: 'POST',
			data: ({
				action: 'getData',
				nonce: SimpleBasket.nonce
			}),
			success: function (data)
			{
				if (callback) callback(data);
			}
		});
	}

	// Метод добавляет товар в корзину
	SimpleBasket.add = function (id, callback)
	{
		if (!id) return;
		jQuery.ajax({
			url: SimpleBasket.ajaxurl,
			type: 'POST',
			data: ({
				action: 'add',
				nonce: SimpleBasket.nonce,
				'id': id 
			}),
			success: function (data)
			{
				console.log(data);
				if (callback) callback(data);
			}
		});
	}


});