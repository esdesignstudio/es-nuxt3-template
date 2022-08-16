<?php
function get_page_support($request)
{
    $response['status'] = 200;

    $ID = 2230;

    $fields = get_fields($ID);
    
    $response['data'] = $fields;

    return new WP_REST_Response($response, $response['status']);
}
