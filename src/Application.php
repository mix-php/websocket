<?php

namespace Mix\WebSocket;

use Mix\Core\Application\ComponentInitializeTrait;

/**
 * Class Application
 * @package Mix\WebSocket
 * @author liu,jian <coder.keda@gmail.com>
 */
class Application extends \Mix\Core\Application
{

    use ComponentInitializeTrait;

    /**
     * 执行握手
     * @param $ws
     * @param $request
     * @param $response
     */
    public function runHandshake($ws, $request, $response)
    {
        $interceptor = \Mix::$app->registry->getInterceptor();
        $interceptor->handshake($ws, $request, $response);
    }

    /**
     * 执行连接开启
     * @param $ws
     * @param $request
     */
    public function runOpen($ws, $request)
    {
        $handler = \Mix::$app->registry->getHandler();
        $handler->open($ws, $request);
    }

    /**
     * 执行消息处理
     * @param $ws
     * @param $frame
     */
    public function runMessage($ws, $frame)
    {
        $handler = \Mix::$app->registry->getHandler();
        $handler->message($ws, $frame);
    }

    /**
     * 执行连接关闭
     * @param $ws
     */
    public function runClose($ws)
    {
        $handler = \Mix::$app->registry->getHandler();
        $handler->close($ws);
    }

}
