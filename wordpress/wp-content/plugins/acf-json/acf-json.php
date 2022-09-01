<?php
/**
 * Plugin Name: acf-json
 * Plugin URI:  
 * Description: Custom ACF json creator
 * Version:     1.0.0
 * Author:      Les Lai
 * Author URI:  
 */

define( 'MY_PLUGIN_DIR_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

add_filter('acf/settings/save_json', 'my_acf_json_save_point');

 
function my_acf_json_save_point( $path ) {
    
    // Update path
    $path = MY_PLUGIN_DIR_PATH. '/../../themes/custom-themes/acf-json';
    // Return path
    return $path;
    
}
 
?>