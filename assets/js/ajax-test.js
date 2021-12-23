jQuery(document).ready(function($) {
          
	$('input.variation_id').change( function(){
	if( '' !== $('input.variation_id').val() ) {
		var var_id = $('input.variation_id').val();
		
				var data = {
					action: 'test_response',
					_ajax_nonce: the_ajax_script.elex_wfp_variation_nonce_token,
					var_id: var_id,
				};
				// the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
				 $.post(the_ajax_script.ajaxurl, data, function(response) {
					response = JSON.parse(response)
					 if (response.general_flag === 'yes' ) {
						if ( response.hide_price === 'yes') {
							$('span.price').css("display", "none")
							$('p.price').css("display", "none")
						 }
						 else {
							 $('p.price').css("display", "block")
						 }
						 jQuery('.woocommerce-variation-price').find('.elex-set-min-price').remove();

						 jQuery('.woocommerce-variation-price').append('<p></p>' + '<div class="wrap-validation elex-set-min-price" >' +
							'<label class="custom-min-price-validation" for="custom_price_field_variation"> ' + response.label + ' (' + response.currency_symbol +')' + '</label>' +
							'<input type="number" step="any" class="custom-price-variation" value="' +response['value'] + '" id="custom_price_field_variation" name="custom_price_field_variation"  />' +
							'<small class="description_product">*'+  response['desc'] +'</small></div>' + '<p></p>' ) 
							}
					 else {
						if ( response.hide_price === 'yes') {
							$('p.price').css("display", "none")
						 }
						 else {
							 $('p.price').css("display", "block")
						 }
					 }
					 					
				 });
				 return false;
		   
		};
	});
});