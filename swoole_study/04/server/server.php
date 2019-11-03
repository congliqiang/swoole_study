<?php
//Linux网络编程的实验中遇到了开启server后用CTRL+C退出但是端口仍被server占用的情况，首先可以用lsof查看占用端口的进程号
//
//	lsof -i:端口号
//1
//然后kill掉占用进程，就可以再次启动server了
//
//	kill -9 进程号
//1
//当然上述还是有些麻烦，因此可以用以下一条命令替代：
//
//	sudo kill -9 $(lsof -i:端口号 -t)
// 安装压测工具
// yum -y install httpd-tools
// 多个客户端发起请求,观察服务器的状态
// ab -n 请求数 -c 并发数 -k 长连接

 class Worker{
     //监听socket
     protected $socket = NULL;
     //连接事件回调
     public $onConnect = NULL;
     //接收消息事件回调
     public $onMessage = NULL;
     public function __construct($socket_address) {
        $this->socket = stream_socket_server($socket_address);
     }

     public function start() {
         while(true){
             $clientSocket = stream_socket_accept($this->socket);
             if (!empty($clientSocket) && is_callable($this->onConnect)){
                 // 触发事件的连接的回调
                 call_user_func($this->onConnect,$clientSocket);
             }

             // 连接中读取客户端的数据
             $buffer = fread($clientSocket,65535);

             // 正常读取到数据,触发消息接收事件,响应内容
             if(!empty($buffer) && is_callable($this->onMessage)){
                 call_user_func($this->onMessage,$clientSocket,$buffer);
             }

             fclose($clientSocket);
         }
         // 连接建立成功触发事件
//         call_user_func($this->onConnect,"参数");
     }
 }



$worker = new Worker('tcp://0.0.0.0:9800');

$worker->onConnect = function ($fd) {
        echo '新的连接来了'.(int)$fd.PHP_EOL;
};
$worker->onMessage = function ($conn, $message) {
    //事件回调中写业务逻辑
//    var_dump($conn,$message);
    $content="你好mrcc";
    $http_resonse = "HTTP/1.1 200 OK\r\n";
    $http_resonse .= "Content-Type: text/html;charset=UTF-8\r\n";
    $http_resonse .= "Connection: keep-alive\r\n";  // 保持连接
    $http_resonse .= "Server: php socket server\r\n";
    $http_resonse .= "Content-length: ".strlen($content)."\r\n\r\n";
    $http_resonse .= $content;
    fwrite($this->conn, $http_resonse);
};
$worker->start();


