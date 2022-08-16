<?php
function get_custom_page($request)
{
    $response['status'] = 200;
    
    $fields = get_fields($request['id']);
    $response['data'] = $fields;

    return new WP_REST_Response($response, $response['status']);
}
