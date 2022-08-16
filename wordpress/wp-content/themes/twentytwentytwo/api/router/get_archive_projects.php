<?php
function get_archive_projects($request)
{
    $response['status'] = 200;

    $post_type = 'projects';

    $fields = get_fields($post_type . '_options');
    $posts = get_posts([
        'post_type' => $post_type,
    ]);

    $taxonomies = array_reduce(get_object_taxonomies($post_type), function ($acc, $taxonomy) {
        $acc[$taxonomy] = get_terms([
            'taxonomy' => [
                $taxonomy,
            ],
            'hide_empty' => true,
        ]);
        return $acc;
    }, []);

    for ($x = 0; $x <= count($taxonomies['color'])-1; $x++) {
        $taxonomies['color'][$x] -> {"hex"} = get_field('color', 'color_' . $taxonomies['color'][$x] -> {"term_id"});
    }

    // get all projects (with ACF fields)
    $projects_args = array(
        'posts_per_page' => -1,
        'post_type'   => 'products',
        'orderby' => 'date',
        'order'  => 'ASC',
        'fields' => 'ids',
    );

    for ($x = 0; $x <= count(get_posts( $projects_args ))-1; $x++) {
        $projects_id[] = get_posts( $projects_args )[$x];   
    }

    for ($x = 0; $x <= count($projects_id)-1; $x++) {
        $products_posts [] = Array(
            "slug" => get_post($projects_id[$x])->post_name,
            "name" => html_entity_decode(get_the_title($projects_id[$x])),
            "term_id" => wp_get_post_categories($projects_id[$x],  array( 'fields' => 'ids' ) ),
            "post" => get_post( $projects_id[$x] ),
            "product_id" => get_field('product_id', $projects_id[$x]),
            "guid" => get_permalink($projects_id[$x]),
            "image" => get_field('list_image', $projects_id[$x]),
            "location" => get_field('location', $projects_id[$x]),
            "categories" => wp_get_post_categories($projects_id[$x],  array( 'fields' => 'names' ) ),
            "specifications" => wp_get_post_terms($projects_id[$x], "specifications"),
            "color" => wp_get_post_terms($projects_id[$x], "color"),
            "space" => wp_get_post_terms($projects_id[$x], "space"),
            "nation" => wp_get_post_terms($projects_id[$x], "nation"),
            "area" => wp_get_post_terms($projects_id[$x], "area"),
            "products" => get_field('products',$projects_id[$x], false)
        );   
    }

    for ($x = 0; $x <= count($projects_id)-1; $x++) {
        $products [] = Array(
            "product_id" => get_posts( $projects_args )[$x],
            "name" => html_entity_decode(get_the_title($projects_id[$x])),
            "term_id" => wp_get_post_categories($projects_id[$x],  array( 'fields' => 'ids' ) ),
        );   
    }

    // set admin_url
    $admin_url = admin_url( 'admin-ajax.php' );

    $response['data'] = (object) [
        'fields' => $fields,
        'posts' => $products_posts,
        'taxonomies' => $taxonomies,
        'products' => $products
    ];

    return new WP_REST_Response($response, $response['status']);
}
