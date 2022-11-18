<?php
function get_page_home($request)
{
    $parameters = $request->get_params();
    $response['status'] = 404;

    // $postID = pll_get_post($requestID, $parameters['locale']);
    $post = get_post($request['id']);
    if ($post) {
        $response['data'] = $post;
        $response['status'] = 200;
    };

    return new WP_REST_Response($response, $response['status']);
}
