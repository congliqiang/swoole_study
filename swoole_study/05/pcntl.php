<?php
/**
 * Created by PhpStorm.
 * User: 小丛
 * Date: 03.11.2019
 * Time: 20:09
 */
// 查看进程
// ps -aux|grep php
$a = 1;
$ppid = posix_getppid(); // 父进程id
echo $ppid.PHP_EOL;
for ($i=0;$i<=5;$i++){
    $pid = pcntl_fork(); //创建成功返回子进程id
    if ($pid < 0){
        exit('创建失败');
    }else if($pid > 0) {
        //父进程空间,返回子进程id
        $a = 2;
        echo "父进程".$a.PHP_EOL;
    }else{
        // 子进程空间
        echo $pid.PHP_EOL;
       sleep(20);
    }
}
