<?php
function get_page_about_banlon($request)
{
    $response['status'] = 200;

    $ID = 2231;
    
    $fields = get_fields($ID);

    
    if ($fields['relatedNews'][0]['news']) {
        $relaNews = array();
        for ($i = 0; $i < count($fields['relatedNews']) ; $i++) { 
            $postID = $fields['relatedNews'][$i]['news']->ID;
            $thumb = get_the_post_thumbnail_url( $postID );

            $fields['relatedNews'][$i]['news']->post_date = get_the_date( 'Y-m-d', $postID );
            $fields['relatedNews'][$i]['news']->thumb_url = $thumb;

            array_push($relaNews, $fields['relatedNews'][$i]['news']);
        }
        $fields['relatedNews'] = $relaNews;
    };

    $response['data'] = $fields;

    return new WP_REST_Response($response, $response['status']);
}
