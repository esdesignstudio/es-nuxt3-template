<?php
function get_single_projects($request)
{
    $response['status'] = 404;
    $response['error'] = 'Page Not Found';

    $post_type = 'projects';
    $post_name = $request['post'];
    $post = get_posts([
        "post_type" => $post_type,
        "name" => $post_name
    ])[0];

    $term = get_the_terms($post->ID, 'category');


    $products_id = get_field('products', $post->ID);

    if (!empty($products_id)) {
        for ($x = 0; $x <= count($products_id)-1; $x++) {
            $products_posts [] = Array(
                "image" => get_field('list_image', $products_id[$x]->ID),
                "name" => html_entity_decode(get_the_title($products_id[$x]->ID)),
                "guid" => get_page_uri( $products_id[$x]->ID ),
                "id" => $products_id[$x]->ID,
            );   
        }
    }

    $projects_id = get_field('projects', $post->ID);

    if (!empty($projects_id)) {
        for ($x = 0; $x <= count($projects_id)-1; $x++) {
            $projects_posts [] = Array(
                "image" => get_field('list_image', $projects_id[$x]->ID),
                "name" => html_entity_decode(get_the_title($projects_id[$x]->ID)),
                "location" => get_field('location', $projects_id[$x]->ID),
                "guid" => get_page_uri( $projects_id[$x]->ID ),
            );   
        }
    }


    if ($post) {
        $response['status'] = 200;
        $fields = get_fields($post);
        $fields['post'] = get_post($post->ID);
        $fields['specifications'] = get_the_terms($post->ID, 'specifications');
        $fields['bolon_studio'] = get_the_terms($post->ID, 'bolon_studio');
        $fields['category'] = Array(
            "name" => get_the_terms($post->ID, 'category')[0] -> name,
            "description" => get_the_terms($post->ID, 'category')[0] -> description,
            "banner" => get_field('banner', array_pop($term)),
        );

        if (!empty($products_id)) {
            $fields['products'] = $products_posts;
        }
        if (!empty($projects_id)) {
            $fields['projects'] = $projects_posts;
        }


        $response['data'] = $fields;
    }

    return new WP_REST_Response($response, $response['status']);
}
