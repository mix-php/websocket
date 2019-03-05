<?php

namespace Mix\WebSocket;

use Mix\Core\Component\AbstractComponent;
use Mix\Core\Component\ComponentInterface;
use Mix\WebSocket\Server\SwooleEvent;

/**
 * Class Error
 * @package Mix\WebSocket
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class Error extends AbstractComponent
{

    /**
     * 协程模式
     * @var int
     */
    public static $coroutineMode = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 错误级别
     * @var int
     */
    public $level = E_ALL;

    /**
     * 异常处理
     * @param $e
     */
    public function handleException($e)
    {
        // 握手错误
        if (\Mix::$app->has('response') && \Mix::$app->response->getStatus() == ComponentInterface::STATUS_RUNNING) {
            \Mix::$app->response->statusCode = 500;
            \Mix::$app->response->send();
            return;
        }
        // 其他错误
        
    }

}
