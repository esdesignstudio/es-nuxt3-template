<?php
function get_global($request)
{
    $response['status'] = 200;
    $response['data'] = get_fields('option');

    return new WP_REST_Response(
        rest_ensure_response($response), 
        $response['status']
    );
}
