<?php
/*
 * (c) U.E Dream Development Studio
 *
 * Author: 李益达 - Ekey.Lee <ekey.lee@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace app\socketio\controller;

// require_once VENDOR_PATH . "workerman/phpsocket.io/src/autoload.php";

use PHPSocketIO\SocketIO;
use think\Db;
use Workerman\Worker;
use Workerman\Lib\Timer; // 引入WM框架的类库

class Server
{

    public function index()
    {
        $io = new SocketIO(2346);//socket的端口
        $io->on('workerStart', function () use ($io) {
            $inner_http_worker = new Worker('http://127.0.0.1:5880');//这里IP不用改变，用的内网通讯，端口不能与socket端口想通
            $inner_http_worker->onMessage = function ($http_connection, $data) use ($io) {
                $io->emit('new_data', 'groupling.net');//这里写了固定数据，请根据自己项目需求去做调整，不懂这里的可以看看官方文档，很清楚
                $http_connection->send('send ok');
            };
            $inner_http_worker->listen();
        });

        // 当有客户端连接时
        $io->on('connection', function ($socket) use ($io) {
            // 定义chat message事件回调函数
//            $socket->on('chat message', function ($msg) use ($io) {
//                // 触发所有客户端定义的chat message from server事件
//                $io->emit('chat message from server', $msg);
//            });

            //每分钟 实时推送数据房源
            Timer::add(60, function ()use ($io){
                $where['push_state'] = 0;
                $data = DB::name('lease_info')
                    ->where($where)
                    ->order('id desc')
                    ->limit(1)
                    ->select();

                $io->emit('new_data',json_encode($data));

                $ids = array_column($data,'id');
                DB::name('lease_info')
                    ->whereIn('id',$ids)
                    ->update(['push_state'=>1]);
            });
        });

        Worker::runAll();
    }
}
