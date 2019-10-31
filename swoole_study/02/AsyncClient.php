<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 29.10.2019
 * Time: 21:59
 */
$client = new swoole\Client(SWOOLE_SOCK_TCP,SWOOLE_SOCK_ASYNC);

//连接事件,必须注册所有事件
$client->on("connect", function (swoole_client $cli){
    $cli->send("GET / HTTP/1.1\r\n\r\n");
});

$client->on("receive", function (swoole_client $cli, $data){
    echo "Receive:".$data;
//    $cli->send(str_repeat('A', 100)."\n");
//    sleep(1);
});
$client->on("error", function (swoole_client $cli){
    echo "error\n";
});
$client->on("close",function (swoole_client $cli){
    echo "Connection close\n";
});
$client->connect("127.0.0.1", 9800);
// 定时器
swoole_timer_tick(9000,function () use($client){
    $client->send('1');
});
echo "写日志";

echo "请求api接口获取数据";