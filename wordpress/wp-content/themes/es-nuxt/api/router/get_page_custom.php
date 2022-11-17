<?php
function get_page_custom($request)
{
    $parameters = $request->get_params();

    $post = get_post($request['id']);
    // $postID = pll_get_post($requestID, $parameters['locale']);

    if ($post) {
        $fields = get_fields($postID);
        $fields['post'] = get_post($postID);
        $response = $fields;
    }

    return new WP_REST_Response(
        rest_ensure_response($response), 
        $response['status']
    );
}
