<?php
function get_single_work($request)
{
    $response['status'] = 404;
    $parameters = $request->get_params();

    $post = get_posts([
        "post_type" => 'works',
        "name" => $parameters['slug']
    ])[0];

    $post_id = pll_get_post($post->ID, $parameters['locale']);
    $acfs = get_fields($post);

    if ($post) {
        $response['status'] = 200;
        $fields = $acfs;
        $fields['post'] = $post;
        $fields['nextmain'] = get_field('main', $acfs['nextprev']['next']->ID);
        $fields['workscate'] = get_the_terms($post, 'works-cate')[0];

        $response['data'] = $fields;
    }

    return new WP_REST_Response(
        rest_ensure_response($response), 
        $response['status']
    );
}
