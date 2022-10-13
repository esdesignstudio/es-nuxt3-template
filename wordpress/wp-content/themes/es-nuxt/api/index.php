<?php
require_once 'router/get_single_work.php';
require_once 'router/get_page_custom.php';
require_once 'router/get_archive_works.php';
require_once 'router/get_page_home.php';
require_once 'router/get_global.php';


/**
 * origin api
 * wp-json/wp/v2/[router]
 */

add_action('rest_api_init', function () {

    register_rest_route('api', '/get_global', array(
        'methods' => 'GET',
        'callback' => 'get_global'
    ));
    
    register_rest_route('api', '/get_page_custom', array(
        'methods' => 'POST',
        'callback' => 'get_page_custom'
    ));
    
    register_rest_route('api', '/get_page_home', array(
        'methods' => 'POST',
        'callback' => 'get_page_home'
    ));

    register_rest_route('api', '/get_archive_works', array(
        'methods' => 'POST',
        'callback' => 'get_archive_works'
    ));

    register_rest_route('api', '/get_single_work', array(
        'methods' => 'POST',
        'callback' => 'get_single_work'
    ));

});
