<?php

function api_send_mail($request)
{
    $response = array();
    $parameters = $request->get_json_params();
    // print_r($parameters['formValue']);
    $formValue = json_decode(urldecode($parameters['formValue']));
    $language = sanitize_text_field($parameters['language']);
    $error = new WP_Error();

    if (empty($formValue)) {
        $error->add(400, 'formValue is required and need to be correctly encoded.', array('status' => 400));
        return $error;
    }

    if (empty($formValue->userMail) || empty($formValue->userMail->value)) {
        $error->add(400, 'userMail is required.', array('status' => 400));
        return $error;
    }

    if (empty($formValue->userName) || empty($formValue->userName->value)) {
        $error->add(400, 'userName is required.', array('status' => 400));
        return $error;
    }

    /** 變數說明
     *
     *  @param Object $formValue 回傳的 Object
     */
    // 使用者確認信 User
    // 客戶留底信 Spare
    // $formValue->userMail 使用者輸入的信箱，根據前端的 key 值更換
    // $formValue->userName 使用者輸入的名字

    $userMailList = array( // 取出使用者輸入的信箱
        $formValue->userMail->value
    );
    $spareMailList = array( // 客戶需要留底的信箱陣列
        // get_option('contactEmail')
        'hi@e-s.tw',
    );

    // // ****** 處理信件標題、內容 開始 ******

    $split = $language == 'en' ? ',' : '、'; // 根據語言決定符號使用
    $form_content = api_helper_generate_form_value_html($formValue, $split);

    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $spareMailData = array();
    $userMailData = array();
    switch ($language) {
        case 'en':
            $spareMailData = array(
                'subject' => sprintf('【%s】New application', $blogname), // 使用者確認信主旨
                'message' => api_helper_format_mail('New application received', $form_content, false)
            );
            $userMailData = array(
                'subject' => sprintf('【%s】We have received your information', $blogname), // 客戶留底信主旨，若有不同類型的表單，信件主旨加上前綴
                'message' => api_helper_format_mail('Your information has been received', 'The information you filled in is as follows:<br><br>' . $form_content)
            );
            break;
        case 'zh':
        default:
            $spareMailData = array(
                'subject' => sprintf('【%s】有新的申請', $blogname), // 使用者確認信主旨
                'message' => api_helper_format_mail('收到新的申請', $form_content, false)
            );
            $userMailData = array(
                'subject' => sprintf('【%s】我們已收到您的資料', $blogname), // 客戶留底信主旨，若有不同類型的表單，信件主旨加上前綴
                'message' => api_helper_format_mail('已收到您的資料', '我們已收到您的資料，您填寫的資料如下：<br><br>' . $form_content)
            );
            break;
    }

    // ****** 處理信件標題、內容 開始 ******


    // ****** 處理 header 開始 ******

    $userHeaders = array('From:' . $blogname . ' <' . $spareMailList[0] . '>', 'Content-Type: text/html; charset=UTF-8'); // 使用者確認信headers

    $userName = $formValue->userName->value; // 取得使用者輸入的名字
    $fromName = '=?UTF-8?B?' . base64_encode($userName) . '?='; // 防止中文亂碼處理
    $spareHeaders = array('From:' . $fromName . ' <' . $userMailList[0] . '>', 'Content-Type: text/html; charset=UTF-8'); // 客戶留底信 headers

    // ****** 處理 header 結束 ******

    // ****** 寄信開始 ******

    foreach ($spareMailList as $spareMail) { // 遍歷陣列送客戶留底信
        $spareMailResults[] = wp_mail($spareMail, $spareMailData['subject'], $spareMailData['message'], $spareHeaders);
    }
    foreach ($userMailList as $userMail) { // 遍歷陣列送使用者確認信
        $userMailResults[] = wp_mail($userMail, $userMailData['subject'], $userMailData['message'], $userHeaders);
    }

    // ****** 寄信結束 ******


    $response['spareMailResults'] = $spareMailResults;
    $response['userMailResults'] = $userMailResults;

    return new WP_REST_Response($response, 123);
}

function api_helper_generate_form_value_html($formValue, $split = '、')
{
    $content = '';

    foreach ($formValue as $key => $value) {
        // if ($key == 'userMail') {
        //     // 特殊 key 值不輸出
        //     continue;
        // }

        // 目前的資料結構每個問卷的欄位 $value 都是 Object，包含 {value：傳送數值} 與 {label：顯示標籤}

        $fillLabel = $value->label;
        $fillValue = $value->value;

        $content = $content . $fillLabel . ' : ';
        if (is_array($fillValue)) {
            $length = count($fillValue);
            foreach ($fillValue as $subKey => $subValue) {
                if ($subKey == $length - 1) {
                    $content = $content . $subValue;
                } else {
                    $content = $content . $subValue . $split;
                }
            }
            $content = $content . '<br>';
        } else {
            $content = $content . $fillValue . '<br>';
        }
    }
    return $content;
}

function api_helper_format_mail($title, $content, $show_signature = true)
{
    // 若前後端分離：需在 docker_composer.yml 定義前端的 URL
    // $front_url = constant('FRONT_SITEURL');
    $front_url = get_home_url(); // 前後端統一使用 wp

    $logo_url = $front_url . '/mail-logo.jpg';
    $logo = '<img src="' . $logo_url . '" alt="logo" width="224" height="60" style="display: block; margin-bottom: 28px;" />';

    $message = $logo;
    $message .= '<h1 style="font-size:30px; color: #333333;">' . $title . '</h1>';

    $message .= '<div style="font-size: 16px; line-height: 1.63; color: #333333;">' . $content;

    if ($show_signature) {
        $message .= '<br><br>' . '若有疑問請利用下方資訊與我們聯繫' . '<br>' .
            get_option('blogname') . '<br>' .
            get_option('phone') . '<br>' .
            get_option('email') . '<br>' .
            $front_url;
    }

    $message .= '</div>';

    return $message;
}
