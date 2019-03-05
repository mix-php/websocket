<?php

namespace Mix\WebSocket\Registry;

use Mix\Http\Message\Request;
use Mix\Http\Message\Response;

/**
 * Interface InterceptorInterface
 * @package Mix\WebSocket\Registry
 * @author LIUJIAN <coder.keda@gmail.com>
 */
interface InterceptorInterface
{

    /**
     * 握手
     * @param Request $request
     */
    public function handshake(Request $request, Response $response);

}
