<?php
namespace app\common\controller;

use think\Controller;

class Base extends Controller
{
	/**
     * 生成json格式API返回值
     *
     * @param mixed $code 状态码
     * @param string $msg 提示信息
     * @param array $data 数据
     *
     * @return string
     */
    public function apiReturn($code, $msg = "", $data = [])
    {
        $GLOBALS['t2'] = microtime(true);
        $time = round($GLOBALS['t2'] - $GLOBALS['t1'], 4);

        if (is_array($code)) { //增加对数组形式的结果格式化
            $result = &$code;
        } else if (is_numeric($code)) {
            $result = array(
                'code' => $code,
                'msg' => $msg,
                'data' => $data
            );
        } else {
            return '';
        }

        $result['time'] = (string)$time;

        return json_encode($result);
    }

    /**
     * 成功结果格式化
     *
     * @param array  $data
     * @param string $message
     * @param int    $code
     *
     * @return array
     */
    public function successJson( $data = [],  $message = '',  $code = 200)
    {
       
        $result =  [
            'code' 		=> $code,
            'success'   => true,
            'data' 		=> $data,
            'message'   => $message
        ];

        return json_encode($result);
    }

    /**
     * 失败结果格式化
     *
     * @param array  $data
     * @param string $message
     * @param int    $code
     *
     * @return array
     */
    public function errorJson( $message = '',  $code = 400,  $data = [])
    {
       
        $result =  [
            'code' 		=> $code,
            'success'   => false,
            'data' 		=> $data,
            'message'   => $message
        ];

        return json_encode($result);
    }
}
