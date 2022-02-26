<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use \think\Route;

Route::get('v1/cities/:cityName','v1/Index/city');

Route::get('v1/cities','v1/Index/cities');

Route::get('v1/houses/:uid','v1/Index/index');

Route::post('v1/houses','v1/Index/index');

Route::get('api/notices/last','api/notices/last');

Route::get('api/account/oauth-url','api/account/oauthUrl');