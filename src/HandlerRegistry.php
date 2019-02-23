<?php

namespace Mix\WebSocket;

use Mix\Core\Component\AbstractComponent;
use Mix\Core\Component\ComponentInterface;

/**
 * Class HandlerRegistry
 * @package Mix\WebSocket
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class HandlerRegistry extends AbstractComponent
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


    public function intercept()
    {
        $interceptor = $this->getInterceptor();
    }

    public function handle()
    {
        $handler = $this->getHandler();

    }

    protected function getAction()
    {
        return \Mix::$app->request->server('path_info', '');
    }

    protected function getInterceptor()
    {
        $action = $this->getAction();
        if (isset($this->_interceptors[$action])) {
            return $this->_interceptors[$action];
        }
        $interceptorName = $this->rules[$action]['interceptor'] ?? null;
        
    }

    protected function getHandler()
    {
        $action = $this->getAction();
        if (isset($this->_handlers[$action])) {
            return $this->_handlers[$action];
        }
        $handlerName = array_shift($this->rules[$action]);

    }

}
