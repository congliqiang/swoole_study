<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 29.10.2019
 * Time: 21:59
 */
$client = new swoole\Client(SWOOLE_SOCK_TCP);

$client->connect("127.0.0.1", 9800);
for($i = 0;$i<= 10;$i++){
    $client->send("123456");
}
//echo $client->recv(); //接收消息没有收到