<?php
// 创建api触发socketio
namespace app\socketio\controller;

class Index
{
    public function api()
    {
        // 推送的url地址，使用自己的服务器地址
        $push_api_url = "http://127.0.0.1:5880";//这里同样不需要更改IP。只是端口一定需要和server.php onworker的一样
        $post_data    = array(
            "type"    => "new_data",
            "content" => "这个是推送的测试数据",
        );
        $ch           = curl_init();
        curl_setopt($ch, CURLOPT_URL, $push_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        $return = curl_exec($ch);
        curl_close($ch);
        var_export($return);
    }
}
