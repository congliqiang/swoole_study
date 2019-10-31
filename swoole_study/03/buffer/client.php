<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 29.10.2019
 * Time: 21:59
 */
$client = new swoole\Client(SWOOLE_SOCK_TCP);

$client->connect("127.0.0.1", 9800);
// 1.不要再客户端没有确认的情况下发送多次消息

// 约定一个分隔符
// 一次性发多条数据
//for($i = 0;$i<= 10;$i++){
//    $client->send("123456\r\n");
//}
// 一次发送大量数据,拆分成小数据
$body = json_encode(str_repeat('a',1024*1024*2));
// 数据包头+包体
$data = pack('N',strlen($body)).$body;
$client->send($data);
$client->send($data);
$client->send($data);
echo $client->recv(); //接收消息没有收到