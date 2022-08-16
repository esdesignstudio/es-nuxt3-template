<?php
function get_archive_products($request)
{
    $response['status'] = 200;

    $post_type = 'products';

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

    // get all products (with ACF fields)
    $products_args = array(
        'posts_per_page' => -1,
        'post_type'   => 'products',
        'orderby' => 'date',
        'order'  => 'DESC',
        'fields' => 'ids',
    );

    for ($x = 0; $x <= count(get_posts( $products_args ))-1; $x++) {
        $products_id[] = get_posts( $products_args )[$x];   
    }

    for ($x = 0; $x <= count($products_id)-1; $x++) {
        $products_posts [] = Array(
            "slug" => get_post($products_id[$x])->post_name,
            "id" => $products_id[$x],
            "guid" => get_permalink($products_id[$x]),
            "name" => html_entity_decode(get_the_title($products_id[$x])),
            "product_id" => get_field('product_id', $products_id[$x]),
            "image" => get_field('list_image', $products_id[$x]),
            "categories" => wp_get_post_categories($products_id[$x],  array( 'fields' => 'names' ) ),
            "specifications" => wp_get_post_terms($products_id[$x], "specifications"),
            "color" => wp_get_post_terms($products_id[$x], "color"),
            "space" => wp_get_post_terms($products_id[$x], "space"),
            "new" => get_field('new', $products_id[$x]),
        );   
    }

    // set admin_url
    $admin_url = admin_url( 'admin-ajax.php' );

    $response['data'] = (object) [
        'fields' => $fields,
        'posts' => $products_posts,
        'taxonomies' => $taxonomies
    ];

    return new WP_REST_Response($response, $response['status']);
}
