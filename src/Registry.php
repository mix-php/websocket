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
        // 设置组件状态
        $this->setStatus(ComponentInterface::STATUS_RUNNING);
    }

    /**
     * 前置处理事件
     */
    public function onBeforeInitialize()
    {
        // 移除设置组件状态
    }

    /**
     * 握手处理
     * @param $request
     * @param $response
     */
    public function handleHandshake($request, $response)
    {
        $interceptor = $this->getInterceptor();
        $interceptor->handshake($request, $response);
    }

    /**
     * 连接开启处理
     * @param $ws
     * @param $request
     */
    public function handleOpen($ws, $request)
    {
        $handler = $this->getHandler();
        $handler->open($ws, $request);
    }

    /**
     * 消息处理
     * @param $ws
     * @param $frame
     */
    public function handleMessage($ws, $frame)
    {
        $handler = $this->getHandler();
        $handler->message($ws, $frame);
    }

    /**
     * 连接关闭处理
     * @param $ws
     */
    public function handleClose($ws)
    {
        $handler = $this->getHandler();
        $handler->close($ws);
    }

    /**
     * 获取规则
     * @return array
     */
    protected function getRule()
    {
        $connections = &$this->_connections;
        $rules = &$this->rules;
        $fd = $this->_fd;
        if (isset($connections[$fd])) {
            return $connections[$fd];
        }
        $action = \Mix::$app->request->server('path_info', '/');
        if (!isset($rules[$action])) {
            throw new \Mix\Exception\NotFoundException("'{$action}' No registration Handler.");
        }
        return $connections[$fd] = $rules[$action];
    }

    /**
     * 获取拦截器
     * @return InterceptorInterface
     */
    protected function getInterceptor()
    {
        $interceptors = &$this->_interceptors;
        $rule = $this->getRule();
        $interceptorName = $rule['interceptor'] ?? '';
        $interceptorClass = "{$this->interceptorNamespace}\\{$interceptorName}";
        if (isset($interceptors[$interceptorClass])) {
            return $interceptors[$interceptorClass];
        }
        if (!class_exists($interceptorClass)) {
            throw new \RuntimeException("'interceptor' not found: {$interceptorClass}");
        }
        $interceptor = new $interceptorClass;
        if (!($interceptor instanceof InterceptorInterface)) {
            throw new \RuntimeException("{$interceptorClass} type is not 'Mix\WebSocket\Registry\InterceptorInterface'");
        }
        return $interceptors[$interceptorClass] = $interceptor;
    }

    /**
     * 获取处理器
     * @return HandlerInterface
     */
    protected function getHandler()
    {
        $handlers = &$this->_handlers;
        $rule = $this->getRule();
        $tmp = $rule;
        $handlerName = array_shift($tmp);
        $handlerClass = "{$this->handlerNamespace}\\{$handlerName}";
        if (isset($handlers[$handlerClass])) {
            return $handlers[$handlerClass];
        }
        if (!class_exists($handlerClass)) {
            throw new \RuntimeException("'handler' not found: {$handlerClass}");
        }
        $handler = new $handlerClass;
        if (!($handler instanceof HandlerInterface)) {
            throw new \RuntimeException("{$handlerClass} type is not 'Mix\WebSocket\Registry\HandlerInterface'");
        }
        return $handlers[$handlerClass] = $handler;
    }

}
