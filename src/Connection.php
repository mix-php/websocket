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
        $data = $this->swooleConnection->recv();
        if ($data === false) {
            $this->close();
            $errCode = swoole_last_error();
            $errMsg  = swoole_strerror($code, 9);
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
        $closeFrame = new \Swoole\WebSocket\CloseFrame();
        return $this->send($closeFrame);
    }

}
