<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 29.10.2019
 * Time: 21:59
 */

$server = new Swoole\Server("0.0.0.0", 9800);

//$server->set([
//    'worker_num'=>1, //设置进程
//    'heartbeat_idle_time' => 10, //连接最大的空闲时间
//    'heartbeat_check_interval'=>3, //服务器定时检查(每3秒检测)
//    'open_eof_check'    =>  true,
//    'package_eof'   =>  "\r\n",
//    'open_eof_split'    =>  true,
//]);
$server->set([
    'worker_num'=>1, //设置进程
//    'heartbeat_idle_time' => 10, //连接最大的空闲时间
//    'heartbeat_check_interval'=>3, //服务器定时检查(每3秒检测)
    'open_length_check' =>  true, // 打开包长度检测特性
    'package_max_length'    =>  2*1024*1024, // 包的最大长度
    'package_length_type'   =>  'N', //数据包头部的长度
    'package_length_offset'  => 0,  //整体长度起始位置
    'package_body_offset'   =>  4,  //包体的长度起始位置
]);

$server->on('connect', function ($server,$fd){
    echo "新的连接进入:{$fd}".PHP_EOL;
});

$server->on('receive',function (swoole_server $server,int $fd,int $reactor_id,string $data){
    var_dump("消息发送过来:".strlen($data));

    $info = unpack('N',$data);
    var_dump($info);
    $server->send($fd,'我是服务端');
});

$server->on('close',function (){
    echo "消息关闭".PHP_EOL;
});
//服务器开启
$server->start();