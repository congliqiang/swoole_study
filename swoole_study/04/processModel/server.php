<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 02.11.2019
 * Time: 10:57
 */
//tcp协议
$server=new Swoole\Server("0.0.0.0",9800);   //创建server对象

//$server->set([
//    'worker_num'=>1, //设置进程
//    //'heartbeat_idle_time'=>10,//连接最大的空闲时间
//    //'heartbeat_check_interval'=>3 //服务器定时检查
//    'open_eof_check'=>true,
//    'packge_eof'=>"\r\n",
//    'open_eof_split'=>true
//]);

$server->set([
    'worker_num'=>3, //设置进程
    //'heartbeat_idle_time'=>10,//连接最大的空闲时间
    //'heartbeat_check_interval'=>3 //服务器定时检查
    'open_length_check'=>1,
    'package_length_type'=>'N',//设置包头的长度
    'package_length_offset'=>0, //包长度从哪里开始计算
    'package_body_offset'=>4,  //包体从第几个字节开始计算
    'package_max_length'=>1024 * 1024 * 2,

]);


$server->on("Start",function (){

    var_dump(1);
    //设置主进程的名称
    swoole_set_process_name("server-process:master");
});

//服务关闭时候触发(信号)
$server->on("shutdown",function (){
});


//当管理进程启动时调用它
$server->on('ManagerStart',function (){
    var_dump(2);
    //swoole_set_process_name("server-process:manger");
});

$server->on('WorkerStart',function ($server,$workerId){
    // swoole_set_process_name("server-process:worker");
    var_dump(3);
});



//监听事件,连接事件(woker进程当中)
$server->on('connect',function ($server,$fd){
    echo "新的连接进入:{$fd}".PHP_EOL;
});


//消息发送过来(woker进程当中)
$server->on('receive',function (swoole_server $server, int $fd, int $reactor_id, string $data){
    //var_dump("消息发送过来:".$data);
    //服务端
});

//消息关闭
$server->on('close',function (){
    echo "消息关闭".PHP_EOL;
});
//服务器开启
$server->start();



echo '123456';