<?php
function get_archive_blog($request)
{
    $parameters = $request->get_params();

    $cat_slug = $parameters['cat_slug'];
    $paged = $parameters['page'];

    $args = array(
        'post_type' => 'blog',
        'posts_per_page' => $parameters['posts_per_page'],
        'paged' => $paged,
    );
    
    if ($cat_slug === 'all') {
        // 取得所有文章
        $posts = get_posts($args);

    } else { 
        // 取得分類文章
        $cat_slug = $parameters['cat_slug'];
        $args = array(
            'post_type' => 'blog',
            'posts_per_page' => $parameters['posts_per_page'],
            'paged' => $paged,
            'tax_query' => array(
                array(
                    'taxonomy' => 'blog-category',
                    'field'    => 'slug',
                    'operator' => 'IN',
                    'terms'    => $cat_slug
                )
            )
        );
        $term = get_term_by('slug', $cat_slug, 'blog-category');
        $posts = get_posts($args);
        $response['cate_title'] = $term->name; 
    }

    // 重新組裝文章
    $resData = array();
    foreach($posts as $post) {
        $resData[] = array(
            'id'       => $post->ID,
            'title'    => $post->post_title,
            'slug'     => $post->post_name,
            'date'     => $post->post_date,
            'category' => wp_get_post_terms( $post->ID, 'blog-category'),
            // 'fields'  => get_fields($post->ID) // 客制欄位
        );
    };
    $response['categories'] = get_terms(array(
        'taxonomy' => 'blog-category',
        'hide_empty' => false,
    ));

    $response['posts'] = $resData;
    $response['paginations'] = es_get_posts_pagination($args, $paged);

    return new WP_REST_Response(
        $response
    );
}
