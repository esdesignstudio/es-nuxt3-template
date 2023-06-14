<?php
function get_archive_blog($request)
{
    $parameters = $request->get_params();
    $paged = $parameters['page'];

    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $parameters['posts_per_page'],
        'paged' => $paged,
    );

    if (isset($parameters['cat_slug'])) {
        $cat_slug = $parameters['cat_slug'];
        if ($cat_slug !== 'all') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'blog-category',
                    'field'    => 'slug',
                    'operator' => 'IN',
                    'terms'    => $cat_slug
                )
            );
            $term = get_term_by('slug', $cat_slug, 'blog-category');
            $response['cate_title'] = $term->name;
        }
    }

    $posts = get_posts($args);
    
    $resData = array();
    foreach ($posts as $post) {
        $resData[] = array(
            'id'       => $post->ID,
            'title'    => $post->post_title,
            'slug'     => $post->post_name,
            'date'     => $post->post_date,
            'category' => wp_get_post_terms($post->ID, 'blog-category'),
        );
    }

    $response['categories'] = get_terms(array(
        'taxonomy'   => 'blog-category',
        'hide_empty' => false,
    ));

    $response['posts'] = $resData;
    $response['paginations'] = es_get_posts_pagination($args, $paged);

    return new WP_REST_Response($response);
}
