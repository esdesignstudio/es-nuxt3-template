<?php
require_once 'router/get_global_options.php';
require_once 'router/get_page_index.php';
require_once 'router/get_archive_works.php';
require_once 'router/get_single_works.php';
require_once 'router/custom_mail.php';

/**
 * origin api
 * wp-json/wp/v2/[router]
 */

add_action('rest_api_init', function () {

    register_rest_route('api', '/global_options', array(
        'methods' => 'GET',
        'callback' => 'get_global_options'
    ));

    register_rest_route('api', '/index', [
        'methods' => 'GET',
        'callback' => 'get_page_index'
    ]);

    register_rest_route('api', '/works', [
        'methods' => 'POST',
        'callback' => 'get_archive_works'
    ]);

    register_rest_route('api', '/works-post/(?P<post>(%[0-9A-F]{2}|[^<>\'" %])+)', [
        'methods' => 'GET',
        'callback' => 'get_single_works'
    ]);

    register_rest_route('api', '/mail', [
        'methods' => 'POST',
        'callback' => 'custom_mail'
    ]);
});
