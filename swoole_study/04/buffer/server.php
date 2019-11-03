<?php
/**
 * Created by PhpStorm.
 * User: Sixstar-Peter
 * Date: 2019/2/19
 * Time: 21:37
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
    'worker_num'=>1, //设置进程
    //'heartbeat_idle_time'=>10,//连接最大的空闲时间
    //'heartbeat_check_interval'=>3 //服务器定时检查
    'open_length_check'=>1,
    'package_length_type'=>'N',//设置包头的长度
    'package_length_offset'=>0, //包长度从哪里开始计算
    'package_body_offset'=>4,  //包体从第几个字节开始计算
    'package_max_length'=>1024 * 1024 * 11,
    'buffer_output_size'=>1024 * 1024 * 11, //输出缓冲区的大小
]);


//监听事件,连接事件
$server->on('connect',function ($server,$fd){
    echo "新的连接进入:{$fd}".PHP_EOL;
});


//消息发送过来
$server->on('receive',function (swoole_server $server, int $fd, int $reactor_id, string $data){
    //var_dump("消息发送过来:".$data);
    //服务端

    sleep(5);
    //解包，并且截取数据包，截取的长度就是包头的长度
    $info=unpack('N',$data);
    var_dump('长度',$info);
    //var_dump(substr($data,4));
    $server->send($fd,$data);
    $server->send($fd,$data);
    $server->send($fd,$data);
    $server->close($fd);
});

//消息关闭
$server->on('close',function (){
    echo "消息关闭".PHP_EOL;
});
//服务器开启
$server->start();

