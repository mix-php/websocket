<?php

namespace Mix\WebSocket;

use Mix\WebSocket\Exception\CloseException;
use Mix\WebSocket\Exception\CloseFrameException;
use Mix\WebSocket\Exception\ReceiveException;
use Mix\WebSocket\Exception\SendException;

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
        $frame = $this->swooleResponse->recv();
        if ($frame === false) { // 接收失败
            $this->close();
            $errCode = swoole_last_error();
            $errMsg  = swoole_strerror($errCode, 9);
            throw new ReceiveException($errMsg, $errCode);
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
            throw new ReceiveException($errMsg, $errCode);
        }
        return $frame;
    }

    /**
     * Send
     * @param \Swoole\WebSocket\Frame $data
     */
    public function send(\Swoole\WebSocket\Frame $data)
    {
        $result = $this->swooleResponse->push($data);
        if ($result === false) {
            throw new SendException($this->swooleResponse->socket->errMsg, $this->swooleResponse->socket->errCode);
        }
    }

    /**
     * Close
     */
    public function close()
    {
        if (!$this->swooleResponse->close()) {
            throw new CloseException($this->swooleResponse->socket->errMsg, $this->swooleResponse->socket->errCode);
        }
        $this->connectionManager->remove($this->swooleResponse->fd);
    }

}
