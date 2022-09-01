<?php
function get_page_index($request)
{
    $response['status'] = 200;

    $ID = 2;

    $fields = get_fields($ID);
    $response['data'] = $fields;

    return new WP_REST_Response($response, $response['status']);
}
