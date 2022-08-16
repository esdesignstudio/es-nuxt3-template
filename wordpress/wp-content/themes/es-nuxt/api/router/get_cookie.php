<?php
function get_cookie($request)
{
    $response['status'] = 200;
    $parameters = $request->get_params();
    $products_id = $parameters['products_id'];

    if (!empty($products_id)) {
        for ($x = 0; $x <= count($products_id)-1; $x++) {
            $products_posts [] = Array(
                "image" => get_field('list_image', $products_id[$x]),
                "name" => html_entity_decode(get_the_title($products_id[$x])),
                "guid" => get_page_uri( $products_id[$x] ),
                "id" => $products_id[$x]
            );   
        }
    }

    $response['data'] = $products_posts;

    return new WP_REST_Response($response, $response['status']);
}