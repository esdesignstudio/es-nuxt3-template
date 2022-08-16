<?php
function get_filter_projects($request)
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
    $nation_id = $parameters['nation_id'];
    $area_id = $parameters['area_id'];
    $products_id = $parameters['products_id'];

    $all_category_terms = get_terms('category', ['fields' => 'ids']);
    $all_specifications_terms = get_terms('specifications', ['fields' => 'ids']);
    $all_bolon_studio_terms = get_terms('bolon_studio', ['fields' => 'ids']);
    $all_color_terms = get_terms('color', ['fields' => 'ids']);
    $all_space_terms = get_terms('space', ['fields' => 'ids']);
    $all_nation_terms = get_terms('nation', ['fields' => 'ids']);
    $all_area_terms = get_terms('area', ['fields' => 'ids']);

    $all_posts_args = array(
        'posts_per_page' => -1,
        'post_type' => 'projects',
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
        if (!empty(get_field('products',get_posts( $all_posts_args )[$x], false))) {
            for ($y = 0; $y <= count(get_field('products',get_posts( $all_posts_args )[$x], false))-1; $y++) {
                update_post_meta(
                    get_posts($all_posts_args)[$x],
                    'products_' . get_field('products',get_posts( $all_posts_args )[$x], false)[$y],
                    get_field('products',get_posts( $all_posts_args )[$x], false)[$y]
                );
            }
        }
    }
    
    $args = array(
        'posts_per_page'        => -1, 
        'meta_key'              => empty($products_id) ? '' :'products_' . implode('|',$products_id),
        'orderby'               => 'title',
        'order'                 => 'DESC',
        'post_status'           => 'publish',
        'post_type'             => 'projects',
        'meta_query'            => array(
            'relation'          =>'OR',
            array(
                'key'           => 'title',
                'value'         => $search_text,
                'compare'       => 'LIKE',
            ),
        ),       
        'tax_query'             => array( 
            'relation'          =>'AND',
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
            ),
            array(
                'taxonomy'  => 'nation',
                'field'     => 'term_id',
                'operator' => 'IN',
                'terms'     =>
                    empty($nation_id)
                    ? $all_nation_terms
                    : $nation_id
            ),
            array(
                'taxonomy'  => 'area',
                'field'     => 'term_id',
                'operator' => 'IN',
                'terms'     =>
                    empty($area_id)
                    ? $all_area_terms
                    : $area_id
            ),
        ),
    );

    $resultPosts = get_posts( $args );
    $responPosts = array();

    for ($a=0; $a <= count($resultPosts); $a++) {
        $data = [
            'slug' => $resultPosts[$a]->post_name,
            'ID' => $resultPosts[$a]->ID,
            'name' => $resultPosts[$a]->post_title,
            'location' => get_field('location', $resultPosts[$a]),
            'image' => get_field('list_image', $resultPosts[$a]),
            'product_id' => get_field('product_id', $resultPosts[$a]),
            'categories' => wp_get_post_categories($resultPosts[$a],  array( 'fields' => 'names' ) ),
            'specifications' => wp_get_post_terms($resultPosts[$a]->ID, "specifications"),
            'color' => wp_get_post_terms($resultPosts[$a]->ID, "color"),
            'space' => wp_get_post_terms($resultPosts[$a]->ID, "space"),
            "nation" => wp_get_post_terms($resultPosts[$a]->ID, "nation"),
            "area" => wp_get_post_terms($resultPosts[$a]->ID, "area"),
            "products" => get_field('products',$resultPosts[$a]->ID, false),
            'new' => get_field('new', $resultPosts[$a]->ID),

        ];
        array_push($responPosts, $data);
    }

    $pop = array_pop($responPosts);
    
    $response['data'] = $responPosts;

    return new WP_REST_Response($response, $response['status']);
}