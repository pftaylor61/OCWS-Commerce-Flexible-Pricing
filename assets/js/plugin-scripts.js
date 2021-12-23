
jQuery(document).ready(function(){
   
    jQuery( "#custom_price_field" ).focusout(function() {
            var custom_price_value = jQuery(this).val().replace(',', '.');
        custom_price_value = parseFloat(custom_price_value)
        if (isNaN(custom_price_value)){
        }
        else {
            var return_value = elex_cpp_check_constraint(custom_price_value, parseInt(jQuery(this).attr('min')));
            if ( return_value[0] ){
                jQuery(".price ins span ").html('₹' + custom_price_value.toFixed(2))
            }
        }
    });
});

function elex_cpp_check_constraint(custom_price_value, min_price_value) {
    if (typeof(custom_price_value) === "number"){
        return [true, ""];
    }
    else{
        return [false, ""]
    }
}

jQuery(document).ready(function(){
    jQuery("#custom_price_field").hover(function() {
        jQuery(this).css('cursor','pointer').attr('title', 'Enter price for the current product which needs to be greater than default value.');
    }, function() {
        jQuery(this).css('cursor','auto');
    });
})


jQuery(document).ready(function(){
    jQuery("#wcdpo_settings[custom_value]").hover(function() {
        jQuery(this).css('cursor','pointer').attr('title', 'Enter the minimum price Which is to be set.');
    }, function() {
        jQuery(this).css('cursor','auto');
    });
})


jQuery(document).ready(function() {
    jQuery('#elex_cpp_settings_enable_min_product_price').hover(function() {
        jQuery(this).css('cursor','pointer').attr('title', 'Click to enable product min price.');
    }, function() {
        jQuery(this).css('cursor','auto');
    });
})


jQuery(document).ready(function() {
    jQuery('#elex_cpp_settings_tab_min_product_price').hover(function() {
        jQuery(this).css('cursor','pointer').attr('title', 'Set the minimum price which the customer can proceed with payment.');
    }, function() {
        jQuery(this).css('cursor','auto');
    });
})








