<?php

use Swoole\Process;

/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/5/6
 * Time: 16:54
 */

class TcpServer
{
    // 系统支持的最大子进程数量
    const MAX_PROCESS = 3;

    //子进程pid数组
    private $pids = [];

    //网络套接字
    private $socket;
    // 主进程id
    private $mpid;

    public function run()
    {
        $process = new Process(function(){
            $this->mpid = getmypid();
            // $this->mpid = posix_getpid();
            echo time() , " Master process, pid {$this->mpid}\n";

            // 创建tcp服务器并获取套接字
            $this->socket = stream_socket_server('tcp://127.0.0.1:9503', $errno, $errstr);
            if (!$this->socket) {
                echo "server start error: {$errstr} --- {$errno}\n";
                throw new \Swoole\ExitException($errstr, $errno);
            }

            for ($i = 0; $i < self::MAX_PROCESS; $i++) {
                $this->startWorkerProcess();
            }

            echo "waiting client start ... \n";

            while(1) {
                foreach ($this->pids as $k => $pid) {
                    if ($pid) {
                       // 回收结束的子进程，防止出现僵尸进程
                       $ret = Process::wait(false);
                       if ($ret) {
                           echo time(), "worker process {$pid} exit, will start new ...\n";
                           // 子进程退出后重启一个新的子进程
                           $this->startWorkerProcess();
                           unset($this->pids[$k]);
                       }
                    }
                    sleep(1); //让出1s给cpu
                }
            }
        }, false, false);   //不启用管道通信

        // 让当前进程变为一个守护进程
        Process::daemon();

        // 执行fork调用，启动进程
        // start 之后的变量，子进程无法获取
        $process->start();
    }

    private function startWorkerProcess()
    {
        $process = new Process(function(Process $worker){
           // 子进程业务处理
            $this->acceptClient($worker);
        });

        // 启动子进程并获取子进程pid
        $pid = $process->start();
        $this->pids[] = $pid;
    }

    private function acceptClient(&$worker)
    {
        while(1) {
            // 从主进程创建的网络套机子上获取连接
            $conn = stream_socket_accept($this->socket, -1);
            // 如果定义了连接建立回调函数，则在连接上执行该回调
            if ($this->onConnect) {
                call_user_func($this->onConnect, $conn);
            }

            //循环读取客户端请求消息
            $recv = ''; //实际收到的消息
            $buffer = '';   //缓冲消息
            while(1) {
                //检查主进程是否正常，不正常就退出
                $this->checkMpid($worker);

                $buffer = fread($conn, 20);
                if ($buffer === false || $buffer === '') {
                    if ($this->onClose) {
                        call_user_func($this->onClose, $conn);
                    }
                    // 结束读取消息，退出当前循环，等待下一个客户端连接
                    break;
                }
            }

            $pos = strpos($buffer, "\n");
            if ($pos === false) {   //没有读完，继续读
                $recv .= $buffer;
            } else {    //读取完毕，开始处理请求消息
                $recv .= trim(substr($buffer, 0, $pos + 1));

                if ($this->onMessage) {
                    call_user_func($this->onMessage, $conn, $recv);
                }

                if ($recv == 'quit') {
                    echo "client close connection\n";
                    fclose($conn);
                    break;
                }

                $recv = '';//消息清空，准备接受下一次请求
            }
        }
    }

    public function checkMpid(&$worker) {
        // 检测进程是否存在
        if (!Process::kill($this->mpid, 0)) {
            echo "what happened\n";
            $worker->exit();
        //    这句提示看不到
            echo "master process exited, I 【{$worker['pid']}】 also quit\n";
        }
    }
}

$server = new TcpServer();
$server->onConnect = function($conn) {
    echo "onConnect -- accepted " . stream_socket_get_name($conn, true) . "\n";
};


$server->onMessage = function($conn, $msg) {
    echo "onMessage --" . $msg . "\n";
    fwrite($conn, "received " . $msg . "\n");
};

$server->onClose = function($conn) {
    echo "onCLose " . stream_socket_get_name($conn, true) . "\n";
};

$server->run();