<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 29.10.2019
 * Time: 23:08
 */
$client = new Swoole\Client(SWOOLE_SOCK_UDP);

$client->sendto('127.0.0.1','9800',"我是udp客户端");
echo $client->recv();