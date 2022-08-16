<?php
function send_mail($request)
{
    $parameters = $request->get_params();
    $data = $parameters['form_data'];
    $type = array('上午：' . $data['type']['noon'],'下午：' . $data['type']['afternoon']);
    
    $response['status'] = 200;

    $custome_user_email = array('eason@e-s.tw');
    $subject = '來自 ' . $data['name'] . ' 的詢問';
    $message_headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ES詢問表單 <wordpress@localhost>',
    );
    $message .= '<div style="background-color:#efefef; width:100%;padding-top:50px;padding-bottom:50px;padding-right:20px;padding-left:20px;">';
    $message .= '<style>td{background-color:#fff;border-bottom:1px solid #cccccc;padding:7px 12px;margin:0;color:#5B5B5B;}table{font-size: 14px;width:90%;max-width:600px;margin-right:auto;margin-left:auto;}.title{width:30%;background-color:#5B5B5B;color:#ffffff;}h2{text-align:center;}</style>';
    $message .= '<h2>網站來信詢問內容</h2>';
    $message .= '<table>';
    $message .= '<tr><td class="title">姓名</td><td>' . $data['name'] . '</td></tr>';
    $message .= '<tr><td class="title">聯絡電話</td><td>' . $data['tel'] . '</td></tr>';
    $message .= '<tr><td class="title">電子信箱</td><td>' . $data['email'] . '</td></tr>';
    $message .= '<tr><td class="title">公司名稱/個人</td><td>' . $data['company'] . '</td></tr>';
    $message .= '<tr><td class="title">城市</td><td>' . $data['city'] . '</td></tr>';
    $message .= '<tr><td class="title">您需要什麼協助？</td><td>' . $data['summary'] . '</td></tr>';
    $message .= '<tr><td class="title">詢問產品</td><td>' . implode(", ", $products) . '</td></tr>';
    $message .= '</table>';
    $message .= '</div>';
    $message .= '<p>&nbsp;</p><p style="text-align:center;">此封Email來自於 《EStest》 最新訊息公告郵件。</p>';
    $message .= '</html>';

    $mailResult = false;
    $mailResult = wp_mail($custome_user_email, $subject, $message, $message_headers);

    $response['request'] = $data;

    return new WP_REST_Response($response, $response['status']);
}
