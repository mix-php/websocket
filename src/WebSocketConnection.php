<?php

namespace Mix\WebSocket;

use Mix\Core\Component\AbstractComponent;
use Mix\Core\Component\ComponentInterface;

/**
 * Class WebSocketConnection
 * @package Mix\WebSocket
 * @author liu,jian <coder.keda@gmail.com>
 */
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
        $this->fd     = $fd;
        // 设置组件状态
        $this->setStatus(ComponentInterface::STATUS_RUNNING);
    }

    /**
     * 前置处理事件
     */
    public function onBeforeInitialize()
    {
        // 移除设置组件状态
    }

    /**
     * 发送
     * @param \Swoole\WebSocket\Frame $frame
     * @return bool
     */
    public function push(\Swoole\WebSocket\Frame $frame)
    {
        $fd = $this->fd;
        if (!$this->server->exist($fd) || !$this->server->isEstablished($fd)) {
            return false;
        }
        return $this->server->push($fd, $frame);
    }

    /**
     * 获取客户端信息
     * @return array|bool
     */
    public function getClientInfo()
    {
        return $this->server->getClientInfo($this->fd);
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
