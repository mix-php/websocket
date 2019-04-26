<?php

namespace Mix\WebSocket;

use Mix\Core\Component\ComponentInterface;
use Mix\Core\Component\AbstractComponent;
use Mix\WebSocket\Handler\WebSocketHandlerInterface;
use Mix\WebSocket\Interceptor\WebSocketInterceptorInterface;

/**
 * Class Registry
 * @package Mix\WebSocket
 * @author liu,jian <coder.keda@gmail.com>
 */
class Registry extends AbstractComponent
{

    /**
     * 协程模式
     * @var int
     */
    const COROUTINE_MODE = ComponentInterface::COROUTINE_MODE_REFERENCE;

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
     * 连接信息
     * @var array
     */
    protected $_connections = [];

    /**
     * 文件描述符
     * @var int
     */
    protected $_fd;

    /**
     * 前置初始化
     */
    public function beforeInitialize(int $fd)
    {
        $this->_fd = $fd;
    }

    /**
     * 后置初始化
     */
    public function afterInitialize()
    {
        unset($this->_connections[$this->_fd]);
    }

    /**
     * 获取拦截器
     * @return WebSocketInterceptorInterface
     */
    public function getInterceptor()
    {
        $interceptors     = &$this->_interceptors;
        $rule             = $this->getRule();
        $interceptorName  = $rule['interceptor'] ?? '';
        $interceptorClass = "{$this->interceptorNamespace}\\{$interceptorName}";
        if (isset($interceptors[$interceptorClass])) {
            return $interceptors[$interceptorClass];
        }
        if (!class_exists($interceptorClass)) {
            throw new \RuntimeException("'interceptor' not found: {$interceptorClass}");
        }
        $interceptor = new $interceptorClass;
        if (!($interceptor instanceof WebSocketInterceptorInterface)) {
            throw new \RuntimeException("{$interceptorClass} type is not 'Mix\WebSocket\Interceptor\WebSocketInterceptorInterface'");
        }
        return $interceptors[$interceptorClass] = $interceptor;
    }

    /**
     * 获取处理器
     * @return WebSocketHandlerInterface
     */
    public function getHandler()
    {
        $handlers     = &$this->_handlers;
        $rule         = $this->getRule();
        $tmp          = $rule;
        $handlerName  = array_shift($tmp);
        $handlerClass = "{$this->handlerNamespace}\\{$handlerName}";
        if (isset($handlers[$handlerClass])) {
            return $handlers[$handlerClass];
        }
        if (!class_exists($handlerClass)) {
            throw new \RuntimeException("'handler' not found: {$handlerClass}");
        }
        $handler = new $handlerClass;
        if (!($handler instanceof WebSocketHandlerInterface)) {
            throw new \RuntimeException("{$handlerClass} type is not 'Mix\WebSocket\Handler\WebSocketHandlerInterface'");
        }
        return $handlers[$handlerClass] = $handler;
    }

    /**
     * 获取规则
     * @return array
     */
    protected function getRule()
    {
        $connections = &$this->_connections;
        $rules       = &$this->rules;
        $fd          = $this->_fd;
        if (isset($connections[$fd])) {
            return $connections[$fd];
        }
        $action = '';
        if (\Mix::$app->isRunning('request')) {
            $action = \Mix::$app->request->server('path_info', '/');
        }
        if (!isset($rules[$action])) {
            throw new \Mix\Exception\NotFoundException("'{$action}' No registration Handler.");
        }
        return $connections[$fd] = $rules[$action];
    }

}
