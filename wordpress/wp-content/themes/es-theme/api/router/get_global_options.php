<?php
function get_global_options($request)
{
    $response['status'] = 200;

    $options = get_field('global_options', 'option');
    $menu = get_menu('main_menu');

    $response['data'] = (object) [
        'options' => $options,
        'main_menu' => array_values($menu)
    ];

    return new WP_REST_Response($response, $response['status']);
}
