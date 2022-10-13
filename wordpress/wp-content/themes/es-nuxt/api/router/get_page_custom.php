<?php
function get_page_custom($request)
{
    $response['status'] = 200;
    $parameters = $request->get_params();

    $requestID = $parameters['id'];
    $postID = pll_get_post($requestID, $parameters['locale']);
    $fields = get_fields($postID);
    $fields['post'] = get_post($postID);
    $response['data'] = $fields;

    return new WP_REST_Response(
        rest_ensure_response($response), 
        $response['status']
    );
}
