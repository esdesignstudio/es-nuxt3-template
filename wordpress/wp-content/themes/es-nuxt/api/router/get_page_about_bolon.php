<?php
function get_page_about_bolon($request)
{
    $response['status'] = 200;

    $ID = 2229;

    $fields = get_fields($ID);
    $response['data'] = $fields;

    return new WP_REST_Response($response, $response['status']);
}
