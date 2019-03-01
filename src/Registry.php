<?php

namespace Mix\WebSocket;

use Mix\Core\Component\ComponentInterface;
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
    public function intercept()
    {
        $interceptor = $this->getInterceptor();
        $interceptor->handshake();
    }

    /**
     * 处理消息
     * @param $action
     * @return void
     */
    public function handleMessage()
    {
        $handler = $this->getHandler();
        $handler->message();
    }

    /**
     * 处理连接关闭
     * @param $action
     * @return void
     */
    public function handleConnectionClosed()
    {
        $handler = $this->getHandler();
        $handler->connectionClosed();
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
        $handlerName = array_shift($this->rules[$action]);
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
