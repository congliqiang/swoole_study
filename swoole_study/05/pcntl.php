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
        $status = 0;
        $pid = pcntl_wait($status); // 会返回结束的子进程信息,阻塞状态
        echo "子进程回收了".$pid.PHP_EOL;
    }else{
        // 子进程空间
       sleep(20);
    }
}
