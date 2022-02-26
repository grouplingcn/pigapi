<?php

namespace app\v1\controller;

use app\common\controller\Base;
use think\Db;

class Index extends Base
{
    public function index()
    {
        $uid        = input('get.uid','','trim');
        $keyword    = input('post.keyword');
        $city       = input('post.city');
        $source     = input('post.source');
        $fromPrice  = input('post.fromPrice','','trim');
        $toPrice    = input('post.toPrice','','trim');
        $longitude  = input('post.lng','','trim');
        $latitude   = input('post.lat','','trim');
        if ($fromPrice === '') {
            unset($fromPrice);
        } else{
            $fromPrice = intval($fromPrice);
        }
        if ($toPrice === '') {
            unset($toPrice);
        } else{
            $toPrice = intval($toPrice);
        }


        $where      = [];
        if ($keyword)   $where['title'] = ['like', "%{$keyword}%"];
        if ($city)      $where['city'] = $city;
        if ($uid)       $where['uid'] = $uid;
        if ($source)    $where['source'] = $source;
        if ($longitude) $where['longitude'] = ['between', [$longitude - 0.02, $longitude + 0.02]];
        if ($latitude)  $where['latitude'] = ['between', [$latitude - 0.02, $latitude + 0.02]];
        if (isset($fromPrice) && !isset($toPrice)) $where['price'] = ['>=',$fromPrice];
        if (isset($toPrice) && !isset($fromPrice))   $where['price'] = ['<=',$toPrice];
        if (isset($fromPrice) && isset($toPrice)) $where['price'] = ['between',[$fromPrice,$toPrice]];

        //限制只显示7天内的信息
        $threeDays = date('Y-m-d H:i:s',strtotime('-7 day'));
        $whereTimeLimit['pub_time'] =['>=',$threeDays];
        //限制全市只显示200条
        if ($longitude && $latitude) {
            $limit = 1000;
        }else{
            $limit = 200;
        }
        $order = 'id desc';

        $data = DB::name('lease_info')
            ->where($where)
            ->where($whereTimeLimit)
//            ->field('id',true)
            ->order($order)
            ->limit($limit)
            ->select();

        if ($uid) $data = $data[0];

        return $this->successJson($data);

    }

    public function cities()
    {
        $cityData     = DB::name('city')->select();
        $platformData = DB::name('platform')->field('id,source,display_source AS displaySource')->select();
        foreach ($cityData as $key => $val) {
            $platformArray = explode(',', $val['platform_set']);
            foreach ($platformData as $k => $v) {
                if (in_array($v['id'], $platformArray)) {
                    $cityData[$key]['platform'][] = $v;
                }
            }

        }
        return $this->successJson($cityData);
    }

    public function city()
    {
        $cityName = input('param.cityName', '', 'trim');;

        if (empty($cityName)) return '';

        $cityData = DB::name('city')
            ->where("city = '$cityName'")
            ->select()[0];

        $cityData['sources'] = DB::name('platform')
            ->field('source,display_source AS displaySource')
            ->where('id in (' . $cityData['platform_set'] . ')')
            ->select();

        return $this->successJson($cityData);

    }
}
