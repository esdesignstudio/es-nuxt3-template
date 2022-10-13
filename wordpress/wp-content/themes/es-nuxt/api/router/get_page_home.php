<?php
function get_page_home($request)
{
    $response['status'] = 200;
    $parameters = $request->get_params();

    $requestID = $parameters['id'];
    $postID = pll_get_post($requestID, $parameters['locale']);
    
    $fields = get_fields($postID);

    // 重新組裝作品
    for ($x = 0; $x <= count($fields['work_repeater'])-1; $x++) {
        $workId = $fields['work_repeater'][$x]['work']->ID;
        $fields['work_repeater'][$x] = $fields['work_repeater'][$x]['work'];
        $fields['work_repeater'][$x]->main = get_field('main', $workId);
    }

    $fields['post'] = get_post($postID);
    $response['data'] = $fields;

    return new WP_REST_Response(
        rest_ensure_response($response), 
        $response['status']
    );
}
