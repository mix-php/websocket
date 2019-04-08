<?php

namespace Mix\WebSocket\Handler;

use Mix\Http\Message\Request\HttpRequest;
use Mix\WebSocket\Frame;
use Mix\WebSocket\WebSocketConnection;

/**
 * Interface WebSocketHandlerInterface
 * @package Mix\WebSocket\Handler
 * @author liu,jian <coder.keda@gmail.com>
 */
interface WebSocketHandlerInterface
{

    /**
     * 开启连接
     * @param WebSocketConnection $ws
     * @param HttpRequest $request
     */
    public function open(WebSocketConnection $ws, HttpRequest $request);

    /**
     * 处理消息
     * @param WebSocketConnection $ws
     * @param Frame $frame
     */
    public function message(WebSocketConnection $ws, Frame $frame);

    /**
     * 连接关闭
     * @param WebSocketConnection $ws
     */
    public function close(WebSocketConnection $ws);

}
