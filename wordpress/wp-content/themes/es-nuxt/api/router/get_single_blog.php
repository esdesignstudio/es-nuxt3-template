<?php
function get_single_blog($request)
{
    $parameters = $request->get_params();
    $response['status'] = 404;

    $post = get_posts(array(
        'name' => $parameters['slug'],
        'post_type' => 'blog',
        'post_status' => 'publish'
    ))[0];
    $postID = $post->ID;
    // $postID = pll_get_post($requestID, $parameters['locale']);

    if ($post) {
        $fields = get_fields($postID);
        $fields['post'] = get_post($postID);
        $response = $fields;
        $response['status'] = 200;
    }

    return new WP_REST_Response($post, $response['status']);
}
