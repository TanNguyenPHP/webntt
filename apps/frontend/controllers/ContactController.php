<?php

namespace Corephalcon\Frontend\Controllers;

class ContactController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function checkcaptchaAction()
    {
        $api_url = 'https://www.google.com/recaptcha/api/siteverify';
        $secret_key = $_POST['secret'];
        $remoteip = $_POST['remoteip'];
        //lấy dữ liệu được post lên
        $site_key_post = $_POST['response'];

        //tạo link kết nối
        $api_url = $api_url . '?secret=' . $secret_key . '&response=' . $site_key_post . '&remoteip=' . $remoteip;
        //lấy kết quả trả về từ google
        $response = file_get_contents($api_url);
        //dữ liệu trả về dạng json

        return $this::sendJsonNoConvert($response);
    }
}
