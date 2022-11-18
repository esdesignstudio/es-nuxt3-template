<?php
function get_page_custom($request)
{
    $parameters = $request->get_params();
    $response['status'] = 404;

    $post = get_post($request['id']);
    // $postID = pll_get_post($requestID, $parameters['locale']);
    $postID = $post->ID;

    if ($post) {
        $fields = get_fields($postID);
        $fields['post'] = get_post($postID);
        $response = $fields;
        $response['status'] = 200;
    }

    return new WP_REST_Response($response, $response['status']);
}
