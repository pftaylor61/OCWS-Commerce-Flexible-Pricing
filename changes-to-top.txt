// echo "<b>Please Activate Woocommerce or Classic Commerce</b>";
// exit;
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Hey, Please Login!' );
}
define( 'PLUGIN_NAME', plugin_basename( dirname( __FILE__ ) ) );

require  'includes/scripts.php' ;  //registering and loading scripts
require  'includes/elex-wfp-product-flexible-price.php' ; //Contains all hooks and data filtering functions
require  'includes/elex-wfp-variable-product-flexible-pricing.php' ;
$commerce_dir = 'woocommerce';
if (is_plugin_active('classic-commerce/classic-commerce.php')) {
    $commerce_dir = 'classic-commerce';
}
require_once  WP_PLUGIN_DIR . '/'.$commerce_dir.'/includes/admin/settings/class-wc-settings-page.php' ;
require_once  ABSPATH . 'wp-admin/includes/plugin.php' ;

if ( (is_plugin_active( 'classic-commerce/classic-commerce.php' )) || (is_plugin_active( 'woocommerce/woocommerce.php' )) ) {