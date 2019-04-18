<?php

namespace Mix\WebSocket\Interceptor;

use Mix\Http\Message\Request\HttpRequest;
use Mix\Http\Message\Response\HttpResponse;
use Mix\WebSocket\WebSocketConnection;

/**
 * Interface WebSocketInterceptorInterface
 * @package Mix\WebSocket\Interceptor
 * @author liu,jian <coder.keda@gmail.com>
 */
interface WebSocketInterceptorInterface
{

    /**
     * 握手
     * @param WebSocketConnection $ws
     * @param HttpRequest $request
     * @param HttpResponse $response
     */
    public function handshake(WebSocketConnection $ws, HttpRequest $request, HttpResponse $response);

}
