<?php
require_once 'router/mail.php';
require_once 'router/get_global_options.php';
require_once 'router/get_language.php';
require_once 'router/get_page_index.php';
require_once 'router/get_page_about_bolon.php';
require_once 'router/get_page_about_banlon.php';
require_once 'router/get_page_sustainability.php';
require_once 'router/get_page_support.php';
require_once 'router/get_page_design.php';
require_once 'router/get_archive_products.php';
require_once 'router/get_archive_projects.php';
require_once 'router/get_archive_news.php';
require_once 'router/get_single_products.php';
require_once 'router/get_single_projects.php';
require_once 'router/get_single_news.php';
require_once 'router/get_search.php';
require_once 'router/get_filter_products.php';
require_once 'router/get_filter_projects.php';
require_once 'router/get_cookie.php';
require_once 'router/get_custom_page.php';
require_once 'router/send_mail.php';


/**
 * origin api
 * wp-json/wp/v2/[router]
 */

add_action('rest_api_init', function () {

    register_rest_route('api', '/custom_page/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_custom_page'
    ));

    register_rest_route('api', '/global_options', array(
        'methods' => 'GET',
        'callback' => 'get_global_options'
    ));

    register_rest_route('api', '/index', [
        'methods' => 'GET',
        'callback' => 'get_page_index'
    ]);

    register_rest_route('api', '/sustainability', [
        'methods' => 'GET',
        'callback' => 'get_page_sustainability'
    ]);

    register_rest_route('api', '/support', [
        'methods' => 'GET',
        'callback' => 'get_page_support'
    ]);

    register_rest_route('api', '/design', [
        'methods' => 'GET',
        'callback' => 'get_page_design'
    ]);
    
    register_rest_route('api', '/page_about_banlon', array(
        'methods' => 'GET',
        'callback' => 'get_page_about_banlon'
    ));

    register_rest_route('api', '/page_about_bolon', array(
        'methods' => 'GET',
        'callback' => 'get_page_about_bolon'
    ));

    register_rest_route('api', '/products', [
        'methods' => 'GET',
        'callback' => 'get_archive_products'
    ]);

    register_rest_route('api', '/projects', [
        'methods' => 'GET',
        'callback' => 'get_archive_projects'
    ]);

    register_rest_route('api', '/getnews', [
        'methods' => 'POST',
        'callback' => 'get_archive_news'
    ]);

    register_rest_route('api', '/search', [
        'methods' => 'POST',
        'callback' => 'get_search'
    ]);

    register_rest_route('api', '/products-post/(?P<post>(%[0-9A-F]{2}|[^<>\'" %])+)', [
        'methods' => 'GET',
        'callback' => 'get_single_products'
    ]);

    register_rest_route('api', '/projects-post/(?P<post>(%[0-9A-F]{2}|[^<>\'" %])+)', [
        'methods' => 'GET',
        'callback' => 'get_single_projects'
    ]);

    register_rest_route('api', '/news-post/(?P<post>(%[0-9A-F]{2}|[^<>\'" %])+)', [
        'methods' => 'GET',
        'callback' => 'get_single_news'
    ]);
    
    register_rest_route('api', '/filter-products', [
        'methods' => 'POST',
        'callback' => 'get_filter_products'
    ]);

    register_rest_route('api', '/filter-projects', [
        'methods' => 'POST',
        'callback' => 'get_filter_projects'
    ]);

    register_rest_route('api', '/cookie', [
        'methods' => 'POST',
        'callback' => 'get_cookie'
    ]);

    register_rest_route('api', '/mail', [
        'methods' => 'POST',
        'callback' => 'send_mail'
    ]);
});
