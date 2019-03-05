<?php

namespace Mix\WebSocket;

use Mix\Core\Component\ComponentInterface;
use Mix\Core\Component\AbstractComponent;
use Mix\WebSocket\Registry\HandlerInterface;
use Mix\WebSocket\Registry\InterceptorInterface;

/**
 * Class Registry
 * @package Mix\WebSocket
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class Registry extends AbstractComponent
{

    /**
     * 协程模式
     * @var int
     */
    public static $coroutineMode = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 处理者命名空间
     * @var string
     */
    public $handlerNamespace = '';

    /**
     * 拦截器命名空间
     * @var string
     */
    public $interceptorNamespace = '';

    /**
     * 注册规则
     * @var array
     */
    public $rules = [];

    /**
     * 处理器实例集合
     * @var array
     */
    protected $_handlers = [];

    /**
     * 处理器实例集合
     * @var array
     */
    protected $_interceptors = [];

    /**
     * 拦截
     * @param $action
     * @return void
     */
    public function intercept($request, $response)
    {
        $interceptor = $this->getInterceptor();
        $interceptor->handshake($request, $response);
    }

    /**
     * 处理连接开启
     * @param $action
     * @return void
     */
    public function handleOpen($request)
    {
        $handler = $this->getHandler();
        $handler->open($request);
    }

    /**
     * 处理消息
     * @param $action
     * @return void
     */
    public function handleMessage($frame)
    {
        $handler = $this->getHandler();
        $handler->message($frame);
    }

    /**
     * 处理连接关闭
     * @param $action
     * @return void
     */
    public function handleClose()
    {
        $handler = $this->getHandler();
        $handler->close();
    }

    /**
     * 获取动作
     * @return string
     */
    protected function getAction()
    {
        $key    = 'registry:action';
        $action = \Mix::$app->wsSession->get($key);
        if ($action) {
            return $action;
        }
        $action = \Mix::$app->request->server('path_info', '/');
        \Mix::$app->wsSession->set($key, $action);
        return $action;
    }

    /**
     * 获取拦截器
     * @return InterceptorInterface
     */
    protected function getInterceptor()
    {
        $action = $this->getAction();
        if (isset($this->_interceptors[$action])) {
            return $this->_interceptors[$action];
        }
        $interceptorName = $this->rules[$action]['interceptor'] ?? '';
        $class           = "{$this->interceptorNamespace}\\{$interceptorName}";
        if (!class_exists($class)) {
            throw new \RuntimeException("'interceptor' not found: {$class}");
        }
        $interceptor = new $class;
        if (!($interceptor instanceof InterceptorInterface)) {
            throw new \RuntimeException("{$class} type is not 'Mix\WebSocket\Registry\InterceptorInterface'");
        }
        $this->_interceptors[$action] = $interceptor;
        return $interceptor;
    }

    /**
     * 获取处理器
     * @return HandlerInterface
     */
    protected function getHandler()
    {
        $action = $this->getAction();
        if (isset($this->_handlers[$action])) {
            return $this->_handlers[$action];
        }
        $rule        = $this->rules[$action] ?? [];
        $handlerName = array_shift($rule);
        $class       = "{$this->handlerNamespace}\\{$handlerName}";
        if (!class_exists($class)) {
            throw new \RuntimeException("'handler' not found: {$class}");
        }
        $handler = new $class;
        if (!($handler instanceof HandlerInterface)) {
            throw new \RuntimeException("{$class} type is not 'Mix\WebSocket\Registry\HandlerInterface'");
        }
        $this->_handlers[$action] = $handler;
        return $handler;
    }

}
