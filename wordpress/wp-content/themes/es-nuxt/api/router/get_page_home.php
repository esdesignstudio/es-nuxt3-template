<?php
function get_page_home($request)
{
    $parameters = $request->get_params();

    // $postID = pll_get_post($requestID, $parameters['locale']);
    $post = get_post($request['id']);
    if ($post) {
        $response['data'] = $post;
    };

    return new WP_REST_Response(
        rest_ensure_response($response), 
        $response['status']
    );
}
