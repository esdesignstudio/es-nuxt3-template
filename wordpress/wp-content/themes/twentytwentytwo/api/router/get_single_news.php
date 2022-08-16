<?php
function get_single_news($request)
{
    $response['status'] = 404;
    $response['error'] = 'Page Not Found';

    $post_type = 'news';
    $post_name = $request['post'];
    $post = get_posts([
        "post_type" => $post_type,
        "name" => $post_name
    ])[0];


    $news_id = get_field('relationship', $post->ID);
    $news_posts = array();
    if (!empty($news_id)) {
        for ($x = 0; $x <= count($news_id)-1; $x++) {
            if ($news_id[$x]->ID !== $post->ID) { // 不能放相同文章
                $newsdata = Array(
                    "thumb_url" => get_the_post_thumbnail_url($news_id[$x]->ID),
                    "post_title" => html_entity_decode(get_the_title($news_id[$x]->ID)),
                    "post_date" => get_the_date('Y-m-d', $news_id[$x]->ID ),
                    "post_name" => $news_id[$x]->post_name,
                );
                array_push( $news_posts, $newsdata );
            }
        }
    }
    // 如果沒有指定相關消息，就抓相同分類
    if (count($news_posts) === 0) {
        $taxTerm = get_the_terms($post, 'news-categories');
        $getRelatedPost = get_posts([
            "post_type" => $post_type,
            "numberposts" => 3,
            'exclude'      => array($post->ID),
            "tax_query" => array(
                array(
                    'taxonomy' => 'news-categories',
                    "terms" => $taxTerm[0]->term_id,
                )
            )
        ]);
        for ($x = 0; $x <= count($getRelatedPost)-1; $x++) {
            $relaData = Array(
                "thumb_url" => get_the_post_thumbnail_url($getRelatedPost[$x]->ID),
                "post_title" => html_entity_decode(get_the_title($getRelatedPost[$x]->ID)),
                "post_date" => get_the_date( 'Y-m-d', $getRelatedPost[$x] ),
                "post_name" => $getRelatedPost[$x]->post_name,
            );
            array_push( $news_posts, $relaData );
        }
    }

    if ($post) {
        $response['status'] = 200;
        $fields = get_fields($post);
        $fields['post'] = get_post($post->ID);
        $fields['relationship'] = $news_posts;
        $fields['postDate'] = get_the_date( 'Y-m-d', $post->ID );
        $fields['categories'] = get_the_terms($post->ID, 'news-categories');

        $response['data'] = $fields;
    }

    return new WP_REST_Response($response, $response['status']);
}
