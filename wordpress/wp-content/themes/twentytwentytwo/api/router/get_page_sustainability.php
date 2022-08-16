<?php
function get_page_sustainability($request)
{
    $response['status'] = 200;

    $ID = 285;

    $fields = get_fields($ID);
    
    $response['data'] = $fields;

    return new WP_REST_Response($response, $response['status']);
}
