$(function(){});

$(function() {



	$('.addtocart_quantity').each(function() {

		let spinner = jQuery(this),

			input = spinner.find('input[type="number"]'),

			btnUp = spinner.find('.quantity-up'),

			btnDown = spinner.find('.quantity-down'),

			min = input.attr('min'),

			max = input.attr('max');



		btnUp.click(function() {

			var oldValue = parseFloat(input.val());

			if (oldValue >= max) {

				var newVal = oldValue;

			} else {

				var newVal = oldValue + 1;

			}

			spinner.find("input").val(newVal);

			spinner.find("input").trigger("change");

		});



		btnDown.click(function() {

			var oldValue = parseFloat(input.val());

			if (oldValue <= min) {

				var newVal = oldValue;

			} else {

				var newVal = oldValue - 1;

			}

			spinner.find("input").val(newVal);

			spinner.find("input").trigger("change");

		});

	});



	let element_cart_content = (typeof $('.woocommerce-cart-content').html() !== 'undefined') ? '.woocommerce-cart-content' : '.page-cart-content';



	let element_cart_tbody = (typeof $('.wcmc-cart-tbody').html() !== 'undefined') ? '.wcmc-cart-tbody' : '.page-cart-tbody';



	let element_cart_item = (typeof $('.wcmc-cart__item').html() !== 'undefined') ? '.wcmc-cart__item' : '.cart__item';



	let product_id = 0;



	let product_detail_alert 	=  (typeof $('.wcmc-alert-product').html() !== 'undefined') ? '.wcmc-alert-product' : '.product_alert';

	let product_detail_form  	=  (typeof $('.wcm-box-options').html() !== 'undefined') ? '.wcm-box-options' : '.product_options_form__box';

	let product_detail_form_child	=  (typeof $('.wcmc-product-cart-options').html() !== 'undefined') ? '.wcmc-product-cart-options' : '.product-cart-options';

	let product_detail_btn   	=  (typeof $('.wcmc_add_to_cart').html() !== 'undefined') ? '.wcmc_add_to_cart' : '.product_add_to_cart';

	let product_detail_now   	=  (typeof $('.wcmc_add_to_cart_now').html() !== 'undefined') ? '.wcmc_add_to_cart_now' : '.product_add_to_cart_now';

	let product_detail_price 	=  (typeof $('.wcmc-price-detail').html() !== 'undefined') ? '.wcmc-price-detail' : '.price-detail';

	/**

	 * AddToCartHandler class.

	 */

	let AddToCartHandler = function() {

		$(document)

			.on( 'click', product_detail_form + ' .options .item', this.onAttribute )

			.on( 'mouseover', product_detail_form + ' .options .option-type__swatch', this.upOptionLabel )

			.on( 'click', product_detail_form + ' .options .option-type__radio', this.upDataProduct )

			.on( 'click', product_detail_btn, this.onAddToCart )

			.on( 'click', product_detail_now, this.onAddToCartNow )

	};



	/**================================================================

	 * [ACTION EVENT]

	 * ================================================================

	 */

	AddToCartHandler.prototype.onAttribute = function( e ) {

		$(this).closest('.options').find('label').removeClass('active');

		$(this).closest('label').addClass('active');

		let img = $(this).closest('label').find('.item').attr('data-image');

		if(img !== '' && typeof img != 'undefined') {

			$('.box-image-featured img').attr('src', img);

			$('.zoomWindowContainer .zoomWindow').css('background-image','url("'+img+'")');

			$('#sliderproduct a').removeClass('active');

		}

	};



	AddToCartHandler.prototype.upOptionLabel = function( e ) {

		$(this).closest(product_detail_form_child).find('.option-type__selected-option').text( $(this).attr('data-label') );

	};



	//event khi chọn các option nâng cao

	AddToCartHandler.prototype.upDataProduct = function( e ) {



		let product_form 		 = $(this).closest(product_detail_form);



		let data_product_options = product_form.data('product-options');



		let i = $(this).parent();



		let group   = i.data('group');



		let id      = i.data('id');



		let product_main_id = product_form.data('id');



		let data_product_variations = product_form.data('product-variations');



		let data  = product_form.serializeJSON();



		if(countProperties(data_product_options) !== 0) {



			let icheck = false;



			product_form.find('.options label').each(function(){

				if($(this).attr('data-group') !== group) {

					$(this).addClass('option-type__disabled');

				} else {

					$(this).removeClass('option-type__disabled');

				}

			});



			product_form.find('[class*="option-type__'+group+'_"]').removeClass('option-type__disabled');

			for(let g in data_product_options[group][id]) {

				for(let t in data_product_options[group][id][g]) {

					product_form.find('.option-type__'+g+'_'+data_product_options[group][id][g][t]).removeClass('option-type__disabled');

					if(product_form.find('.option-type__'+g+'_'+data_product_options[group][id][g][t]).hasClass('active') === true ) {

						icheck = true

					}

				}

			}



			if(icheck === false) {

				product_form.find('.options label').each(function(){

					if( $(this).attr('data-group') != group ) {

						$(this).removeClass('active');

						$(this).find('input').prop('checked', false);

					}

				});

			}



			let count_option_item = countProperties(data.option);



			let check_option_item = true;



			for(let k in data_product_variations ) {

				if(countProperties(data_product_variations[k].items) != count_option_item ) {

					check_option_item = false; break;

				}

			}



			if( check_option_item == true ) {



				data.product_id =  $(this).attr('data-id');



				data.action     =  'Cart_Ajax::loadPrice';



				$jqxhr   = $.post(ajax, data, function() {}, 'json');



				$jqxhr.done(function(response) {



					if(response.status === 'success') {



						$(product_detail_price).html(response.data);



						if(typeof response.variation.id != "undefined") {

							let inputID;

							if(typeof product_main_id != "undefined") {

								inputID = $('input#product_select_input_' + product_main_id);

							}

							else {

								inputID = $('.product-detail-cart input[name=product_id]');

							}

							inputID.val(response.variation.id);

							inputID.trigger('change');

						}

					}

				});

			}

		}

	};



	//event khi click vào nút đặt hàng

	AddToCartHandler.prototype.onAddToCart = function( e ) {



		$(product_detail_alert).html('');



		let button = $(this);



		let btnTxt = button.html();



		button.html('<i class="fas fa-circle-notch fa-spin"></i>');



		let data        = $(':input', $(product_detail_form)).serializeJSON();



		data.product_id =  $(this).attr('data-id');



		data.qty 		=  $('#quantity').val();



		if( typeof data.qty == 'undefined' ) data.qty = 1;



		data.action     =  'Cart_Ajax::addCart';



		$.post(ajax, data, function() {}, 'json').done(function(response) {



			button.html(btnTxt);



			show_message(response.message, response.status);



			if(response.status === 'success') {

				$('.wcmc-total-items').html(response.total_items);

				$(element_cart_content + ' ' + element_cart_tbody).html(response.items);

				$('.cart-total-price').html(response.total_label);

				let cart_sidebar = $('#cart-sidebar');

				if(typeof cart_sidebar.html() !== 'undefined') {

					$('.panel--sidebar').removeClass('active');

					$('body').removeClass('panel__sidebar-opened');

					cart_sidebar.addClass('active');

				}

			}

		});



		return false;

	};



	AddToCartHandler.prototype.onAddToCartNow = function( e ) {



		$(product_detail_alert).html('');



		let button = $(this);



		let btnTxt = button.html();



		button.html('<i class="fas fa-circle-notch fa-spin"></i>');



		let data        = $( ':input', $(product_detail_form)).serializeJSON();



		data.product_id =  $(this).attr('data-id');



		data.qty 		=  $('#quantity').val();



		if( typeof data.qty == 'undefined' ) data.qty = 1;



		data.action     =  'Cart_Ajax::addCart';



		$.post(ajax, data, function() {}, 'json').done(function(response) {



			button.html(btnTxt);



			show_message(response.message, response.status);



			if(response.status === 'success') {

				$('.wcmc-total-items').html(response.total_items);

				window.location = 'gio-hang';

			}

		});



		return false;

	};

	/**

	 * Init AddToCartHandler.

	 */

	var addtocart = new AddToCartHandler();



	/**

	 * AddToCartHandler class.

	 */

	let cartInput, cartNumber, cartQty, cartQtyOld, cartQtyClickTimer, cartQtyDoneClickInterval = 500;



	let CartHandler = function() {

		$( document )

			.on('click', '.plus', this.plusQty)

			.on('click', '.minus', this.minusQty)

			.on('click', '.plus, .minus', this.timeClick)

			.on('click', '.cart_item__trash, .js_cart_item__trash_close', this.showButtonDelete)

			.on('click', '.js_cart_item__trash_agree', this.deleteItem)

	};



	CartHandler.prototype.plusQty = function(e) {

		let box   = $(this).closest('.quantity');

		cartInput   = box.find('input.qty');

		cartNumber  = box.find('div.qty');

		cartQty     = parseInt(cartInput.val());

		cartQtyOld = cartQty;

		cartQty += 1;

		cartInput.val(cartQty);

		cartNumber.text(cartQty);

		return false;

	};



	CartHandler.prototype.minusQty = function(e) {

		let box   = $(this).closest('.quantity');

		cartInput   = box.find('input.qty');

		cartNumber  = box.find('div.qty');

		cartQty     = parseInt(cartInput.val());

		cartQtyOld = cartQty;

		if(cartQty > 1 ) {

			cartQty -= 1;

			cartInput.val(cartQty);

			cartNumber.text(cartQty);

		}

		return false;

	};



	CartHandler.prototype.timeClick = function(e) {

		clearTimeout(cartQtyClickTimer);

		cartQtyClickTimer = setTimeout(CartHandler.prototype.qtyDoneClick($(this)), cartQtyDoneClickInterval);

	};



	CartHandler.prototype.qtyDoneClick = function(e) {



		$(element_cart_content + ' .loading').show();



		let box   = e.closest(element_cart_item);



		let data = {

			'action' : 'Cart_Ajax::updateQuantity',

			'rowid'  : box.find('input[name="rowid"]').val(),

			'qty'    : box.find('input.qty').val(),

		};



		$.post(base + '/ajax', data, function() {}, 'json').done(function(response) {



			$(element_cart_content + ' .loading').hide();



			console.log(response);



			if(response.status === 'success') {

				box.find('.js_cart_item_price').html((response.price).toLocaleString());

				$('#cart-total-price, .cart-total-price').html(response.total);

				$('#summary-cart-total-price, .summary-cart-total-price').html(response.summary_total);

			}

			else {



				$('.cart-error').html(response.message.error);



				cartInput.val(cartQtyOld);



				cartNumber.text(cartQtyOld);

			}

		});

	};



	CartHandler.prototype.showButtonDelete = function(e) {

		$(this).closest(element_cart_item).find('.cart_item__trash_popover').toggleClass('active');

	};



	CartHandler.prototype.deleteItem = function(e) {



		let box   = $(this).closest(element_cart_item);



		let data = {

			'action' : 'Cart_Ajax::updateQuantity',

			'rowid'  : box.find('input[name="rowid"]').val(),

			'qty'    : 0

		};



		$.post(base + '/ajax', data, function() {}, 'json').done(function(response) {



			if(response.status === 'success') {



				box.remove();



				if(response.total === 0) {

					location.reload();

				}

				else {

					$('#cart-total-price').html(response.total);

					$('#summary-cart-total-price').html(response.summary_total);

				}

			}

		});

	};



	var cart = new CartHandler();



	/**

	 * AddToCartHandler class.

	 */

	const element_checkout = (typeof $('.woocommerce-checkout').html() !== 'undefined') ? '.woocommerce-checkout' : '.page-checkout';



	const element_review = (typeof $('.wcm-box-order').html() !== 'undefined') ? $('.wcm-box-order') : $('.page-checkout-review');



	const element_checkout_shipping = $('input[name="show-form-shipping"]');



	let CheckoutHandler = function() {



		$( document )

			.on('submit', element_checkout, this.submitCheckout)

			.on('change', '#billing_city', this.loadDistricts)

			.on('change', '#billing_districts', this.loadWard)

			.on('change', '#billing_ward', this.checkoutReview)

			.on('change', '#shipping_city', this.loadShippingDistricts)

			.on('change', '#shipping_districts', this.loadShippingWard)

			.on('change', '#shipping_ward', this.checkoutReview)

			.on('change', element_checkout + ' input[name="shipping_type"]', this.checkoutReview)

			.on('change', element_checkout + ' input[name="show-form-shipping"]', this.checkoutReview)

	};



	CheckoutHandler.prototype.submitCheckout = function(e) {



		let data = $(':input', $(this)).serializeJSON();



		data.action 	= 'Cart_Ajax::saveCheckout';



		element_review.addClass('scmc-loading');



		$.post(ajax, data, function() {}, 'json').done(function(data) {



			element_review.removeClass('scmc-loading');



			$(element_checkout + ' .notice').remove();



			$(element_checkout + ' .toast').remove();



			$('.error_message').html('');



			$('.input_checkout').removeClass('error_show').removeClass('error_input');



			if(data.status === 'success') {



				window.location = data.url;

			}

			else {



				let count_error = 0;



				let notice = data.message;



				for (const [index, message] of Object.entries(notice)) {



					if ( typeof $('#error_' + index).html() != 'undefined') {

						count_error++;

						$('#error_' + index).closest('.input_checkout').addClass('error_input');

						$('#error_' + index).html(message);

					}

					else {

						$(element_checkout).prepend(message);

					}

				}



				if (count_error > 0) {



					$('.input_checkout').addClass('error_show');

				}



				if (typeof $(element_checkout + ' .toast').html() != 'undefined') {

					$('html, body').animate({

						scrollTop: $(element_checkout + ' .toast').offset().top - 100

					}, 500);

				}

				else {



					if (typeof $(element_checkout + ' .error_input').html() != 'undefined') {

						$('html, body').animate({

							scrollTop: $(element_checkout + ' .error_input').offset().top - 100

						}, 500);

					}

				}

			}

		});



		return false;

	};



	CheckoutHandler.prototype.loadDistricts = function(e) {



		let data = {

			province_id: $(this).val(),

			action: 'Cart_Ajax::loadDistricts'

		};



		$jqxhr = $.post(ajax, data, function () { }, 'json');



		$jqxhr.done(function (response) {

			if (response.status === 'success') {

				$('#billing_districts').html(response.data);

				$('#billing_ward').html('<option value="">Chọn phường xã</option>');

				if(element_checkout_shipping.prop('checked') === false) {

					CheckoutHandler.prototype.checkoutReview();

				}

			}

		});

		return false;

	};



	CheckoutHandler.prototype.loadWard = function(e) {

		let data = {

			district_id: $(this).val(),

			action: 'Cart_Ajax::loadWard'

		};

		$.post(ajax, data, function () { }, 'json').done(function (response) {

			if (response.status === 'success') {

				$('#billing_ward').html(response.data);

				if (element_checkout_shipping.prop('checked') === false) {

					CheckoutHandler.prototype.checkoutReview();

				}

			}

		});

		return false;

	};



	CheckoutHandler.prototype.loadShippingDistricts = function(e) {



		let data = {

			province_id: $(this).val(),

			action: 'Cart_Ajax::loadDistricts'

		};



		$.post(ajax, data, function () { }, 'json').done(function (response) {

			if (response.status === 'success') {

				$('#shipping_districts').html(response.data);

				$('#shipping_ward').html('<option value="">Chọn phường xã</option>');

				if(element_checkout_shipping.prop('checked') === true) {

					CheckoutHandler.prototype.checkoutReview();

				}

			}

		});

		return false;

	};



	CheckoutHandler.prototype.loadShippingWard = function(e) {

		let data = {

			district_id: $(this).val(),

			action: 'Cart_Ajax::loadWard'

		};

		$.post(ajax, data, function () { }, 'json').done(function (response) {

			if(response.status === 'success') {

				$('#shipping_ward').html(response.data);

				if(element_checkout_shipping.prop('checked') === true) {

					CheckoutHandler.prototype.checkoutReview();

				}

			}

		});

		return false;

	};



	CheckoutHandler.prototype.checkoutReview = function(e) {



		element_review.addClass('scmc-loading');



		let data = $('form[name="checkout"]').serializeJSON();



		data.action = 'Cart_Ajax::loadCheckoutReview';



		$.post(ajax , data, function() {}, 'json').done(function(response) {



			if(response.type === 'success' ) {



				element_review.html( response.order_review );



				element_review.removeClass('scmc-loading');

			}



		});

	};



	var checkout = new CheckoutHandler();



	if( typeof element_review.html() != 'undefined' ) {

		checkout.checkoutReview();

	}

});

function update_order_review() {

	let element_review = (typeof $('.wcm-box-order').html() !== 'undefined') ? $('.wcm-box-order') : $('.page-checkout-review');

	element_review.addClass('scmc-loading');

	let data = $('form[name="checkout"]').serializeJSON();

	data.action = 'Cart_Ajax::loadCheckoutReview';

	$.post(ajax , data, function() {}, 'json').done(function(response) {

		if(response.type === 'success' ) {

			element_review.html( response.order_review );

			element_review.removeClass('scmc-loading');

		}

	});

}

function wcmc_update_order_review() {

	update_order_review();

}

function countProperties(obj) {

	let prop;

	let propCount = 0;

	for (prop in obj) {

		propCount++;

	}

	return propCount;

}

