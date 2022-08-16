<?php
function get_language($request)
{
    $response = [
        'status' => 404
    ];

    if (function_exists('wpm_get_languages')) {
        $response['status'] = 200;
        $response['data'] = wpm_get_languages();
    }

    return new WP_REST_Response($response, $response['status']);
}
