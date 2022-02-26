<?php
namespace app\api\controller;

class Account
{
    public function oauthUrl(){
        return '{"success":true,"url":"https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101503652&redirect_uri=https%3a%2f%2fhouse-map.cn%2f%23%2fuser%2fcallback&state=qq"}';
    }
}