<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 29.10.2019
 * Time: 21:59
 */
$client = new swoole\Client(SWOOLE_SOCK_TCP);

$client->connect("127.0.0.1", 9800);
// 一次性发多条数据
//for($i = 0;$i<= 10;$i++){
//    $client->send("123456");
//}

// 一次发送大量数据,拆分成小数据
$data = str_repeat('A',12*1024*1024);
$client->send(json_encode($data));
//echo $client->recv(); //接收消息没有收到