<?php
function get_archive_news($request)
{
    $parameters = $request->get_params();
    $cat_slug = $parameters['cat_slug'];
    $response['status'] = 200;

    $post_type = 'news';

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

    $all_category_terms = get_terms('news-categories', ['fields' => 'slugs']);

    //get all news (with ACF fields)
    $news_args = array(
        'posts_per_page' => -1,
        'post_type'   => $post_type,
        'fields' => 'ids',
        'tax_query' => array(
            'relation'          =>'AND',
            array(
                'taxonomy'      => 'news-categories',
                'field'         => 'slug',
                'operator'      => 'IN',
                'terms'         =>
                    empty($cat_slug)
                    ? $all_category_terms
                    : $cat_slug
            ),
        )
    );

    for ($x = 0; $x <= count(get_posts( $news_args ))-1; $x++) {
        $news_id[] = get_posts( $news_args )[$x];   
    }

    for ($x = 0; $x <= count($news_id)-1; $x++) {
        $news_posts [] = Array(
            "title" => html_entity_decode(get_the_title($news_id[$x])),
            "imageUrl" => get_the_post_thumbnail_url( $news_id[$x] ),
            "guid" => get_permalink( $news_id[$x] ),
            "categories" => wp_get_post_terms( $news_id[$x], 'news-categories'),
            "postDate" => get_the_date( 'Y-m-d', $news_id[$x] ),
            "postinfo" => get_post( $news_id[$x] )
            // "cover" => get_field('cover', $news_id[$x]),
            // "post" => get_post( $news_id[$x] ),
        );
    }

    $response['data'] = (object) [
        'feilds' => $fields,
        'posts' => $news_posts,
        "categories" => get_terms(array( 'taxonomy' => 'news-categories' )),
    ];

    return new WP_REST_Response($response, $response['status']);
}
