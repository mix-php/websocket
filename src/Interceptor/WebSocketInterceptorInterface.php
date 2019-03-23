<?php

namespace Mix\WebSocket\Interceptor;

use Mix\Http\Message\Request;
use Mix\Http\Message\Response;

/**
 * Interface WebSocketInterceptorInterface
 * @package Mix\WebSocket\Interceptor
 * @author LIUJIAN <coder.keda@gmail.com>
 */
interface WebSocketInterceptorInterface
{

    /**
     * 握手
     * @param Request $request
     */
    public function handshake(Request $request, Response $response);

}
