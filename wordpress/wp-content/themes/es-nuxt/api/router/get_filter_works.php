<?php
function get_filter_works($request)
{
    $response['status'] = 200;
    $parameters = $request->get_params();

    // 取得 works
    $works_posts = get_posts(array(
        'posts_per_page' => -1,
        'post_type'   => 'works',
        'tax_query' => array(
            'relation'          =>'AND',
            array(
                'taxonomy'      => 'works-cate',
                'field'         => 'slug',
                'operator'      => 'IN',
                'terms'         => $parameters['term']
            ),
        ))
    );

    // 增加 acf 欄位
    for ($x = 0; $x <= count($works_posts)-1; $x++) {
        $workId = $works_posts[$x]->ID;
        $works_posts[$x]->main = get_field('main', $workId);
    }

    $response['data'] = $works_posts;

    return new WP_REST_Response(
        rest_ensure_response($response), 
        $response['status']
    );
}
