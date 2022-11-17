<?php
function get_archive_news($request)
{
    $parameters = $request->get_params();

    $cat_slug = $parameters['cat_slug'];
    $paged = $parameters['page'];

    $args = array(
        'post_type' => 'news',
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
            'post_type' => 'news',
            'posts_per_page' => $parameters['posts_per_page'],
            'paged' => $paged,
            'tax_query' => array(
                array(
                    'taxonomy' => 'news-category',
                    'field'    => 'slug',
                    'operator' => 'IN',
                    'terms'    => $cat_slug
                )
            )
        );
        $posts = get_posts($args);
    }

    $taxonomies = get_terms(array(
        'taxonomy' => 'news-category',
    ));

    // 重新組裝文章
    $resData = array();
    foreach($posts as $post) {
        $resData[] = array(
            'title'    => $post->post_title,
            'slug'     => $post->post_name,
            'date'     => $post->post_date,
            'category' => wp_get_post_terms( $post->ID, 'news-category'),
            'fields'  => get_fields($post->ID) // 客制欄位
        );
    };

    $response['status'] = 200;
    $response['data'] = $resData;
    $response['categories'] = $taxonomies;
    $response['paginations'] = es_get_posts_pagination($args, $paged);

    return new WP_REST_Response($response, $response['status']);
}
