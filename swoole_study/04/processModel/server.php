<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 02.11.2019
 * Time: 10:57
 */
$server = new Swoole\Server("0.0.0.0","9800");

$server->set([
    'worker_num'    =>  1, //设置进程
    'heartbeat_idle_time'   =>  10, //设置最大的空闲时间
    'heartbeat_idle_check_interval' =>  3, //服务器定时检查
    'open_length_check' =>  1,
    'package_length_type'   =>  'N', //设置包头的长度
    'package_length_offset' =>  0, //包长度从哪里开始计算
    'package_body_offset'   =>  4,  //包体从第几个字节开始计算
    'package_max_length'    =>  1024*1024*2,
]);

$server->on("Start",function (){
    //设置主进程的名称
    swoole_set_process_name("server-process:");
});

//服务关闭时触发(信号)
$server->on("shutdown",function (){

});

// 当管理进程启动时调用它
$server->on("ManagerStart",function (){
    swoole_set_process_name("server-process:manager");
});



$server->on("WorkerStart",function ($server,$workerId){
    var_dump($server);
    swoole_set_process_name("server-process:worker");
});


$server->on("connect",function ($serve, $fd){
    echo "新的连接进入:{$fd}".PHP_EOL;
});

$server->start();