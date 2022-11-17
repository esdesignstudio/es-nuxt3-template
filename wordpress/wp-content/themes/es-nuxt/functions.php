<?php
/**
 * ES nuxt theme
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package WordPress
 */

add_filter( 'acf/settings/rest_api_format', function () {
    return 'standard';
});

// Cros
add_filter( 'allowed_http_origins', 'add_allowed_origins' );
function add_allowed_origins( $origins ) {
    $origins[] = 'https://e-s.tw';
    $origins[] = 'http://192.168.50.5:3000';
    $origins[] = 'http://localhost:3000';
    $origins[] = 'http://220.135.109.247';
    $origins[] = 'http://test.e-s.tw';
    return $origins;
};

// 引入
// include_once "setting/cpt.php"; 
include_once "setting/acf.php"; 
include_once "setting/dashboard.php"; 
include_once "setting/admin.php"; 
include_once "api/index.php"; 