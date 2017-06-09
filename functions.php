<?php
/**
 * @package   Gantry 5 Theme
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
 * @license   GNU/GPLv2 and later
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') or die;

// Note: This file must be PHP 5.2 compatible.

// Check min. required version of Gantry 5
$requiredGantryVersion = '5.4.0';

// Bootstrap Gantry framework or fail gracefully.
$gantry_include = get_stylesheet_directory() . '/includes/gantry.php';
if (!file_exists($gantry_include)) {
    $gantry_include = get_template_directory() . '/includes/gantry.php';
}
$gantry = include_once $gantry_include;

if (!$gantry) {
    return;
}

if (!$gantry->isCompatible($requiredGantryVersion)) {
    $current_theme = wp_get_theme();
    $error = sprintf(__('Please upgrade Gantry 5 Framework to v%s (or later) before using %s theme!', 'g5_helium'), strtoupper($requiredGantryVersion), $current_theme->get('Name'));

    if(is_admin()) {
        add_action('admin_notices', function () use ($error) {
            echo '<div class="error"><p>' . $error . '</p></div>';
        });
    } else {
        wp_die($error);
    }
}

/** @var \Gantry\Framework\Theme $theme */
$theme = $gantry['theme'];

// Theme helper files that can contain useful methods or filters
$helpers = array(
    'includes/helper.php', // General helper file
);

foreach ($helpers as $file) {
    if (!$filepath = locate_template($file)) {
        trigger_error(sprintf(__('Error locating %s for inclusion', 'g5_helium'), $file), E_USER_ERROR);
    }

    require $filepath;
}
add_filter( 'woocommerce_enqueue_styles', '__return_false' );


add_filter('woocommerce_product_add_to_cart_text','my_woocommerce_variable_text_button',10,2);
 function my_woocommerce_variable_text_button($text,$product){

if($product->product_type == 'variable'){
 $text = 'Купить';
 }

return $text;
 }
 // стиль для названий списка товаров
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'custom_woocommerce_template_loop_product_title', 10 );
function custom_woocommerce_template_loop_product_title() {
echo '<h2 class="product_title_h2">' . get_the_title() . '</h2>';
}
add_action( 'template_redirect', 'yith_wcqv_remove_button' );
function yith_wcqv_remove_button(){
	if( function_exists('YITH_WCQV_Frontend') ) {
		remove_action( 'woocommerce_after_shop_loop_item', array( YITH_WCQV_Frontend(), 'yith_add_quick_view_button' ), 15 );
	}
}

 // Расположение описания
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 30);