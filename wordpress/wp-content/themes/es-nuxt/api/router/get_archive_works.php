<?php
function get_archive_works($request)
{
    $parameters = $request->get_params();
    $category_id = $parameters['term_id'];
    $per_page = $parameters['per_page'];
    $paged = $parameters['paged'];
    $response['status'] = 200;

    $post_type = 'works';

    $fields = get_fields($post_type . '_options');

    $taxonomies = array_reduce(get_object_taxonomies($post_type), function ($acc, $taxonomy) {
        $acc[$taxonomy] = get_terms([
            'taxonomy' => [
                $taxonomy,
            ],
            'hide_empty' => true,
        ]);
        return $acc;
    }, []);

    $all_category_terms = get_terms('category', ['fields' => 'ids']);

    // get all works (with ACF fields)
    $works_args = array(
        'post_type' => $post_type,
        'fields'    => 'ids',
        'tax_query' => array(
            'relation'          =>'AND',
            array(
                'taxonomy'      => 'category',
                'field'         => 'term_id',
                'operator'      => 'IN',
                'terms'         =>
                    $category_id === 0
                    ? $all_category_terms
                    : $category_id
            ),
        ),
        'posts_per_page' => $per_page,
        'paged' => $paged,
    );

    for ($x = 0; $x <= count(get_posts( $works_args ))-1; $x++) {
        $works_id[] = get_posts( $works_args )[$x];   
    }

    for ($x = 0; $x <= count($works_id)-1; $x++) {
        $works [] = Array(
            'id' => get_posts( $works_args )[$x],
            'guid' => get_permalink($works_id[$x]),
            'post' => get_post($works_id[$x]),
            'cover' => get_field('cover', $works_id[$x]),
        );   
    }

    $args = array(
        'post_type' => $post_type,
        'tax_query' => array(
            'relation'          =>'AND',
            array(
                'taxonomy'      => 'category',
                'field'         => 'term_id',
                'operator'      => 'IN',
                'terms'         =>
                    $category_id === 0
                    ? $all_category_terms
                    : $category_id
            ),
        ),
        'posts_per_page' => $per_page,
    );

    $test = new WP_Query( $args );

    $response['data'] = (object) [
        'fields' => $fields,
        'works' => $works,
        'taxonomies' => $taxonomies,
        'max_num_pages' => $test -> max_num_pages,
    ];

    $response['request'] = $parameters;

    return new WP_REST_Response($response, $response['status']);
}
