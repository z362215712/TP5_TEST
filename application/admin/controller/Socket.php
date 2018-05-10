<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Log;
use think\Request;
use app\admin\model\Article as ArticleModel;

class Socket extends Controller
{

    private $ip = '127.0.0.1';
    private $port = 1935;

    public function client()
    {
        error_reporting(E_ALL);

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            echo "OK.\n";
        }

        echo "试图连接 '$this->ip' 端口 '$this->port'...\n";
        $result = socket_connect($socket, $this->ip, $this->port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            echo "连接OK\n";
        }

        $in = "Ho\r\n";
        $in .= "first blood\r\n";

        if (!socket_write($socket, $in, strlen($in))) {
            echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            echo "发送到服务器信息成功！\n";
            echo "发送的内容为:<font color='red'>$in</font> <br>";
        }

        while ($out = socket_read($socket, 8192)) {
            echo "接收服务器回传信息成功！\n";
            echo "接受的内容为:", $out;
        }

        echo "关闭SOCKET...\n";
        socket_close($socket);
    }

    public function server()
    {
        set_time_limit(0);
        if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
            echo "socket_create() 失败的原因是:" . socket_strerror($sock) . "\n";
        }

        if (($ret = socket_bind($sock, $this->ip, $this->port)) < 0) {
            echo "socket_bind() 失败的原因是:" . socket_strerror($ret) . "\n";
        }

        if (($ret = socket_listen($sock, 4)) < 0) {
            echo "socket_listen() 失败的原因是:" . socket_strerror($ret) . "\n";
        }

        $count = 0;

        do {
            if (($msgsock = socket_accept($sock)) < 0) {
                echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
                break;
            } else {
                //发到客户端
                $msg = "测试成功！\n";
                socket_write($msgsock, $msg, strlen($msg));

                echo "测试成功了啊\n";
                $buf = socket_read($msgsock, 8192);


                $talkback = "收到的信息:$buf\n";
                echo $talkback;

                if (++$count >= 5) {
                    break;
                };


            }
            //echo $buf;
            socket_close($msgsock);

        } while (true);

        socket_close($sock);
    }

    public function send()
    {
        socket_create_listen();
    }
}
