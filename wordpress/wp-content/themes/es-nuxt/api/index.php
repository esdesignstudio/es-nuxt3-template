<?php
require_once 'router/get_page_custom.php';
require_once 'router/get_global.php';

require_once 'router/get_archive_blog.php';
require_once 'router/get_single_blog.php';

/**
 * origin api
 * wp-json/wp/v2/[router]
 */
// !! 注意，後台「設定->永久連結」需要改成「http://localhost:9000/sample-post/」才可以生效

add_action('rest_api_init', function () {

    register_rest_route('api', '/get_global', array(
        'methods' => 'GET',
        'callback' => 'get_global'
    ));
    
    register_rest_route('api', '/get_page_custom', array(
        'methods' => 'POST',
        'callback' => 'get_page_custom'
    ));
    
    register_rest_route('api', '/get_archive_blog', array(
        'methods' => 'POST',
        'callback' => 'get_archive_blog'
    ));
    
    register_rest_route('api', '/get_single_blog', array(
        'methods' => 'POST',
        'callback' => 'get_single_blog'
    ));
    
});
