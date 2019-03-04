<?php

namespace Mix\WebSocket;

use Mix\Core\Component\AbstractComponent;

class WebSocketConnection extends AbstractComponent
{

    /**
     * 服务
     * @var \Swoole\WebSocket\Server
     */
    public $server;

    /**
     * 文件描述符
     * @var int
     */
    public $fd;

    /**
     * 前置初始化
     * @return void
     */
    public function beforeInitialize(\Swoole\WebSocket\Server $server, int $fd)
    {
        $this->server = $server;
        $this->fd = $fd;
    }

    /**
     * 发送
     * @param \Swoole\WebSocket\Frame $frame
     * @return bool
     */
    public function push(\Swoole\WebSocket\Frame $frame)
    {
        return $this->server->push($this->fd, $frame);
    }
    
    /**
     * 关闭连接
     * @param int $code
     * @param string $reason
     */
    public function disconnect($code = 1000, $reason = '')
    {
        $this->server->disconnect($this->fd, $code, $reason);
    }

}
