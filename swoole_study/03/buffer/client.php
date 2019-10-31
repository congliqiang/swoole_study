<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 29.10.2019
 * Time: 21:59
 */
$client = new swoole\Client(SWOOLE_SOCK_TCP);
$client->set(
    [
        'open_length_check'=>1,
        'package_length_type'=>'N',//设置包头的长度
        'package_length_offset'=>0, //包长度从哪里开始计算
        'package_body_offset'=>4,  //包体从第几个字节开始计算
        'package_max_length'=>1024 * 1024 * 3, // 缓冲区大小不宜设置过大
    ]
);
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