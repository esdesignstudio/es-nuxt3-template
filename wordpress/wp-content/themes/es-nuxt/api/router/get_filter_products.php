<?php
function get_filter_products($request)
{
    $response['status'] = 200;
    $parameters = $request->get_params();

    $search_text = $parameters['search_text'];
    $sort = $parameters['sort'];
    $meta_key = $parameters['meta_key'];
    $category_id = $parameters['category_id'];
    $specifications_id = $parameters['specifications_id'];
    $bolon_studio_id = $parameters['bolon_studio_id'];
    $color_id = $parameters['color_id'];
    $space_id = $parameters['space_id'];

    $all_category_terms = get_terms('category', ['fields' => 'ids']);
    $all_specifications_terms = get_terms('specifications', ['fields' => 'ids']);
    $all_bolon_studio_terms = get_terms('bolon_studio', ['fields' => 'ids']);
    $all_color_terms = get_terms('color', ['fields' => 'ids']);
    $all_space_terms = get_terms('space', ['fields' => 'ids']);

    $all_posts_args = array(
        // 'posts_per_page' => -1,
        'post_type' => 'products',
        'fields'    => 'ids',
    );

    for ($x = 0; $x <= count(get_posts( $all_posts_args ))-1; $x++) {
        update_post_meta(
            get_posts($all_posts_args)[$x],
            'title',
            get_the_title(
                get_posts($all_posts_args)[$x]
            )
        );
        update_post_meta(
            get_posts($all_posts_args)[$x],
            'color',
            get_field('order', 'color_' . wp_get_object_terms(
                get_posts($all_posts_args)[$x],
                'color',
                array('fields' => 'ids', 'orderby' => 'ASC',)
            )[0])
        );
        update_post_meta(
            get_posts($all_posts_args)[$x],
            'new',
            get_field('new', get_posts($all_posts_args)[$x])
        );
    }

    $args = array(
        'posts_per_page'        => -1, 
        'meta_key'              => $meta_key,
        'orderby'               => array(
            $sort => 'DESC',
            // 'date' => 'DESC',
        ),
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
        ),          
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy'      => 'category',
                'field'         => 'term_id',
                'operator' => 'IN',
                'terms'         => 
                    empty($category_id)
                    ? $all_category_terms
                    : $category_id
            ),
            array(
                'taxonomy'  => 'specifications',
                'field'     => 'term_id',
                'operator' => 'IN',
                'terms'     =>
                    empty($specifications_id)
                    ? $all_specifications_terms
                    : $specifications_id
            ),
            array(
                'taxonomy'  => 'bolon_studio',
                'field'     => 'term_id',
                'operator' => 'IN',
                'terms'     =>
                    empty($bolon_studio_id)
                    ? $all_bolon_studio_terms
                    : $bolon_studio_id
            ),
            array(
                'taxonomy'  => 'color',
                'field'     => 'term_id',
                'operator' => 'IN',
                'terms'     =>
                    empty($color_id)
                    ? $all_color_terms
                    : $color_id
            ),
            array(
                'taxonomy'  => 'space',
                'field'     => 'term_id',
                'operator' => 'IN',
                'terms'     =>
                    empty($space_id)
                    ? $all_space_terms
                    : $space_id
            )
        )
    );

    $resultPosts = get_posts( $args );
    $responPosts = array();

    for ($a=0; $a < count($resultPosts); $a++) {
        $data = [
            'slug' => $resultPosts[$a]->post_name,
            'name' => $resultPosts[$a]->post_title,
            'image' => get_field('list_image', $resultPosts[$a]),
            'product_id' => get_field('product_id', $resultPosts[$a]),
            'categories' => wp_get_post_categories($resultPosts[$a],  array( 'fields' => 'names' ) ),
            'specifications' => wp_get_post_terms($resultPosts[$a]->ID, "specifications"),
            'color' => wp_get_post_terms($resultPosts[$a]->ID, "color"),
            'space' => wp_get_post_terms($resultPosts[$a]->ID, "space"),
            'new' => get_field('new', $resultPosts[$a]->ID),
        ];
        array_push($responPosts, $data);
    }
    

    $response['data'] = $responPosts;

    return new WP_REST_Response($response, $response['status']);
}