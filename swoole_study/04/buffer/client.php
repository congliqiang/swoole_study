<?php
/**
 * Created by PhpStorm.
 * User: Sixstar-Peter
 * Date: 2019/2/19
 * Time: 23:05
 */
 $client=new swoole\Client(SWOOLE_SOCK_TCP);

 $client->set(
     [
         'open_length_check'=>1,
         'package_length_type'=>'N',//设置包头的长度
         'package_length_offset'=>0, //包长度从哪里开始计算
         'package_body_offset'=>4,  //包体从第几个字节开始计算
         'package_max_length'=>1024 * 1024 * 11,
         //'socket_buffer_size'=>1024 * 1024 * 11
     ]
 );

 //发数据
 $client->connect('127.0.0.1',9800);

 //1.不要在客户端或者服务端没有确认的情况下发送多次消息
//一次发送大量的数据，拆分小数据
$body=json_encode(str_repeat('a', 1024 * 1024 * 10));
//包头+包体
$data=pack("N",strlen($body)).$body;
$client->send($data);
$client->send($data);
$client->send($data);
//var_dump(strlen($client->recv())); //接收消息没有接收
$client->close();

