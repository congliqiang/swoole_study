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
//select处理方式的单进程非阻塞复用的网络服务器
class Worker{
    //监听socket
    protected $socket = NULL;
    //连接事件回调
    public $onConnect = NULL;
    //接收消息事件回调
    public $onMessage = NULL;
    public $workerNum = 2;
    public $allSocket; // 存放所有的socket
    public function __construct($socket_address) {
        // 监听地址+端口
        $this->socket = stream_socket_server($socket_address);
    }
    // fork 进程
    public function fork(){
        $this->accept();
    }

    public function start() {
        // 获取配置文件
        // 监听进程
        $this->fork();  // 用来创建多个子进程,负责接收请求的
    }

    public function accept(){
        // 第一个需要监听的事件(服务端socket的事件),一旦监听到可读事件之后会触发
        swoole_event_add($this->socket,function ($fd){
           $clientSocket = stream_socket_accept($fd);
           // 监听客户端可读事件
            swoole_event_add($clientSocket,function ($fd){
                // 连接中读取客户端的数据
                $buffer = fread($fd,65535);
                // 如果数据为空, 或者为false,或者不是资源类型
                if(empty($buffer)){
                    if(feof($fd) || !is_resource($fd)){
                        // 触发关闭事件
                        fclose($fd);
                    }
                }
                // 正常读取到数据,触发消息接收事件,响应内容
                if(!empty($buffer) && is_callable($this->onMessage)){
                    call_user_func($this->onMessage,$fd,$buffer);
                }
            });
        });
        echo "非阻塞";
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
    fwrite($conn, $http_resonse);
};
$worker->start();