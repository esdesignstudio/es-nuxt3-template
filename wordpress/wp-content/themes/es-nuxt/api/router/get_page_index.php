<?php
function get_page_index($request)
{
    $response['status'] = 200;

    $ID = 2;

    $fields = get_fields($ID);

    if (!empty($fields['features'])) {
        for ($x = 0; $x <= count($fields['features'])-1; $x++) {
            $features_id[] = $fields['features'][$x]['projects']->ID;   
        }
        
        for ($x = 0; $x <= count($features_id)-1; $x++) {
            $fields['features'][$x]['location'] = get_field("location", $features_id[$x]);
            $fields['features'][$x]['guid'] = get_page_uri($features_id[$x]);
        }
    }

    if (!empty($fields['products'])) {
        for ($x = 0; $x <= count($fields['products'])-1; $x++) {
            $products_id[] = $fields['products'][$x]['products']->ID;   
        }

        for ($x = 0; $x <= count($products_id)-1; $x++) {
            $fields['products'][$x]['guid'] = get_page_uri($products_id[$x]);
        }
    }
    
    $response['data'] = $fields;

    return new WP_REST_Response($response, $response['status']);
}
