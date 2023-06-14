<?php
function get_page_custom($request)
{
    $parameters = $request->get_params();
    $response['status'] = 404;

    $post = get_post($parameters['id']);
    // $post = pll_get_post($parameters['id'], $parameters['locale']); 多國語言使用

    if ($post) {
        $fields = get_fields($post->ID);
        $fields['post'] = $post;

        $response['data'] = $fields;
        $response['status'] = 200;
    }

    return new WP_REST_Response($response, $response['status']);
}
