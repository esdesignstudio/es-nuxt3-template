<?php
function get_page_design($request)
{
    $response['status'] = 200;

    $ID = 2306;

    $fields = get_fields($ID);
    
    $response['data'] = $fields;

    return new WP_REST_Response($response, $response['status']);
}
