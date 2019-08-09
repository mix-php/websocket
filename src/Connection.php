<?php

namespace Mix\WebSocket;

use Mix\WebSocket\Exception\ReceiveException;

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
     * @var bool
     */
    protected $closed = false;

    /**
     * Connection constructor.
     * @param \Swoole\Http\Response $response
     */
    public function __construct(\Swoole\Http\Response $response)
    {
        $this->swooleResponse = $response;
    }

    /**
     * Recv
     * @return mixed
     */
    public function recv()
    {
        if ($this->closed) { // 丢弃关闭后的消息
            $errCode = 104;
            $errMsg  = swoole_strerror($errCode, 9);
            throw new ReceiveException($errMsg, $errCode);
        }
        $data = $this->swooleResponse->recv();
        if ($data === false) { // 接收失败
            $this->close();
            $errCode = swoole_last_error();
            $errMsg  = swoole_strerror($errCode, 9);
            throw new ReceiveException($errMsg, $errCode);
        }
        if ($data === "") { // 连接关闭
            $errCode = 104;
            $errMsg  = swoole_strerror($errCode, 9);
            throw new ReceiveException($errMsg, $errCode);
        }
        return $data;
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
        // 由于 Swoole 并没有提供 $ws->close() 导致只能使用这种怪异的方式关闭连接 : https://wiki.swoole.com/wiki/page/1115.html
        $closeFrame   = new \Swoole\WebSocket\CloseFrame();
        $this->closed = true;
        return $this->send($closeFrame);
    }

}
