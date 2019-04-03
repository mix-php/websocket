<?php

namespace Mix\WebSocket\Interceptor;

use Mix\Http\Message\Request\HttpRequest;
use Mix\Http\Message\Response\HttpResponse;

/**
 * Interface WebSocketInterceptorInterface
 * @package Mix\WebSocket\Interceptor
 * @author liu,jian <coder.keda@gmail.com>
 */
interface WebSocketInterceptorInterface
{

    /**
     * 握手
     * @param Request $request
     */
    public function handshake(Request $request, Response $response);

}
