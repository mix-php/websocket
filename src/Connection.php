<?php

namespace Mix\WebSocket;

use Mix\WebSocket\Exception\CloseFrameException;
use Mix\WebSocket\Exception\ReceiveFailureException;

/**
 * Class Connection
 * @package Mix\WebSocket
 * @author liu,jian <coder.keda@gmail.com>
 */
class Connection
{

    /**
     * @var \Swoole\Http\Response
     */
    public $swooleResponse;

    /**
     * @var ConnectionManager
     */
    public $connectionManager;

    /**
     * @var bool
     */
    protected $closed = false;

    /**
     * Connection constructor.
     * @param \Swoole\Http\Response $response
     */
    public function __construct(\Swoole\Http\Response $response, ConnectionManager $connectionManager)
    {
        $this->swooleResponse    = $response;
        $this->connectionManager = $connectionManager;
    }

    /**
     * Recv
     * @return \Swoole\WebSocket\Frame
     */
    public function recv()
    {
        if ($this->closed) { // 主动关闭, 丢弃关闭后的消息
            $errCode = 104;
            $errMsg  = swoole_strerror($errCode, 9);
            throw new ReceiveFailureException($errMsg, $errCode);
        }
        $frame = $this->swooleResponse->recv();
        if ($frame === false) { // 接收失败
            $this->close();
            $errCode = swoole_last_error();
            $errMsg  = swoole_strerror($errCode, 9);
            throw new ReceiveFailureException($errMsg, $errCode);
        }
        if ($frame instanceof \Swoole\WebSocket\CloseFrame) { // CloseFrame
            $this->close();
            $errCode = $frame->code;
            $errMsg  = $frame->reason;
            throw new CloseFrameException($errMsg, $errCode);
        }
        if ($frame === "") { // 连接关闭
            $this->close();
            $errCode = 104;
            $errMsg  = swoole_strerror($errCode, 9);
            throw new ReceiveFailureException($errMsg, $errCode);
        }
        return $frame;
    }

    /**
     * Send
     * @param $data
     * @return bool
     */
    public function send(\Swoole\WebSocket\Frame $data)
    {
        return $this->swooleResponse->push($data);
    }

    /**
     * Close
     * @return bool
     */
    public function close()
    {
        $this->connectionManager->remove($this->swooleResponse->fd);
        // 由于 Swoole 并没有提供 $ws->close() 导致只能使用这种怪异的方式关闭连接 : https://wiki.swoole.com/wiki/page/1115.html
        if ($this->closed) {
            return false;
        }
        $closeFrame         = new \Swoole\WebSocket\CloseFrame();
        $closeFrame->code   = 1000;
        $closeFrame->reason = '';
        $this->closed       = true;
        return $this->send($closeFrame);
    }

}
