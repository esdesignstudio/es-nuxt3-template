<?php
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    //Co-Authors Plus fix
    add_action ('to/get_terms_orderby/ignore', 'to_get_terms_orderby_ignore_coauthors', 10, 3);
    function to_get_terms_orderby_ignore_coauthors( $ignore, $orderby, $args )
        {
            if( !function_exists('is_plugin_active') )
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            
            if( !   is_plugin_active( 'co-authors-plus/co-authors-plus.php' ))
                return $ignore;
            
            if ( ! isset($args['taxonomy']) ||  count($args['taxonomy']) !==    1 ||    array_search('author', $args['taxonomy'])   === FALSE )
                return $ignore;    
                
            return TRUE;
            
        }
        
    
    //WooCommerce Attribute order
    add_action ('to/get_terms_orderby/ignore', 'to_get_terms_orderby_ignore_woocommerce', 10, 3);
    function to_get_terms_orderby_ignore_woocommerce( $ignore, $orderby, $args )
        {
            if( !function_exists('is_plugin_active') )
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            
            if( !   is_plugin_active( 'woocommerce/woocommerce.php' ))
                return $ignore;
            
            if ( ! function_exists ( 'wc_get_attribute_taxonomies' ) )
                return $ignore;
                
            //create a list of attribute taxonomies
            $attributes =   wc_get_attribute_taxonomies();
            $found_attributex_tax   =   array();
            foreach ( $attributes    as  $attribute ) 
                {
                    $found_attributex_tax[] =   'pa_'   .   $attribute->attribute_name;
                }
            
            if ( ! isset($args['taxonomy']) ||  count($args['taxonomy']) !==    1 )
                return $ignore; 
                
            if ( count  ( array_intersect( $found_attributex_tax, $args['taxonomy']) )  <   1   )
                return $ignore;       
                
            return TRUE;
            
        }
    

?>