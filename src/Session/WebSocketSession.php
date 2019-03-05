<?php

namespace Mix\WebSocket\Session;

use Mix\Core\Component\AbstractComponent;
use Mix\Core\Component\ComponentInterface;

/**
 * Class WebSocketSession
 * @package Mix\WebSocket\Session
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class WebSocketSession extends AbstractComponent
{

    /**
     * 协程模式
     * @var int
     */
    public static $coroutineMode = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 处理器
     * @var \Mix\WebSocket\Session\HandlerInterface
     */
    public $handler;

    /**
     * 文件描述符
     * @var int
     */
    public $fd;

    /**
     * 前置初始化
     */
    public function onBeforeInitialize()
    {
        parent::onBeforeInitialize();
        // 设置fd
        if (\Mix::$app->isRunning('ws')) {
            $this->fd = \Mix::$app->ws->fd;
            return;
        }
        if (\Mix::$app->isRunning('request')) {
            $this->fd = \Mix::$app->request->fd;
            return;
        }
    }

    /**
     * 获取
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        return $this->handler->get($key);
    }

    /**
     * 设置
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        return $this->handler->set($key, $value);
    }

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        return $this->handler->delete($key);
    }

    /**
     * 清除
     * @return bool
     */
    public function clear()
    {
        return $this->handler->clear();
    }

    /**
     * 判断是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->handler->has($key);
    }

}
