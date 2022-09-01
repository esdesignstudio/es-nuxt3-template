<?php
function get_single_works($request)
{
    $response['status'] = 404;
    $response['error'] = 'Page Not Found';

    $post_type = 'works';
    $post_name = $request['post'];
    $post = get_posts([
        "post_type" => $post_type,
        "name" => $post_name
    ])[0];

    $term = get_the_terms($post->ID, 'category');

    if ($post) {
        $response['status'] = 200;
        $fields = get_fields($post);
        $fields['post'] = get_post($post->ID);

        $response['data'] = $fields;
    }

    return new WP_REST_Response($response, $response['status']);
}
