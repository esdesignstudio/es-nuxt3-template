<?php
function get_search($request)
{
    $parameters = $request->get_params();
    $search_text = $parameters['search_text'];
    $response['status'] = 200;

    $ID = 4172;

    $fields = get_fields($ID);
    $allPosts = array();

    $all_products_args = array(
        'post_type' => 'products',
        'fields'    => 'ids',
    );

    for ($x = 0; $x <= count(get_posts( $all_products_args ))-1; $x++) {
        update_post_meta(
            get_posts($all_products_args)[$x],
            'title',
            get_the_title(
                get_posts($all_products_args)[$x]
            )
        );
    }

    $productsArgs = array(
        'posts_per_page'        => -1, 
        'meta_key'              => '',
        'order'                 => 'DESC',
        'post_status'           => 'publish',
        'post_type'             => 'products',
        'meta_query'            => array(
            'relation'          =>'OR',
            array(
                'key'           => 'product_id',
                'value'         => $search_text,
                'compare'       => 'LIKE',
            ),
            array(
                'key'           => 'title',
                'value'         => $search_text,
                'compare'       => 'LIKE',
            ),
        )
    );

    $productsResult = get_posts( $productsArgs );
    $productsPosts = array();

    for ($a=0; $a < count($productsResult); $a++) {
        $data = [
            'slug' => $productsResult[$a]->post_name,
            'name' => $productsResult[$a]->post_title,
            'type' => 'products',
            'image' => get_field('list_image', $productsResult[$a]),
        ];
        array_push($allPosts, $data);
    }

    $projectsArgs = array(
        'posts_per_page'        => -1,
        'orderby'               => 'title',
        'order'                 => 'DESC',
        'post_status'           => 'publish',
        'post_type'             => 'projects',
        "s" => $search_text
    );

    $projectsResult = get_posts( $projectsArgs );

    for ($a=0; $a < count($projectsResult); $a++) {
        $data = [
            'slug' => $projectsResult[$a]->post_name,
            'name' => $projectsResult[$a]->post_title,
            'type' => 'projects',
            'image' => get_field('list_image', $projectsResult[$a]),
        ];
        array_push($allPosts, $data);
    }


    $newsArgs = array(
        'posts_per_page'        => -1,
        'orderby'               => 'title',
        'order'                 => 'DESC',
        'post_status'           => 'publish',
        'post_type'             => 'news',
        "s" => $search_text
    );

    $newsResult = get_posts( $newsArgs );

    for ($a=0; $a < count($newsResult); $a++) {
        $data = [
            'slug' => $newsResult[$a]->post_name,
            'name' => $newsResult[$a]->post_title,
            'type' => 'news',
            'image' => get_field('cover', $newsResult[$a]),
        ];
        array_push($allPosts, $data);
    }

    $response['data'] = (object) [
        'fields' => $fields,
        'posts' => $search_text === '' ? array() : $allPosts,
    ];

    return new WP_REST_Response($response, $response['status']);
}
