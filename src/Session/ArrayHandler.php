<?php

namespace Mix\WebSocket\Session;

use Mix\Core\Component\AbstractComponent;

/**
 * Class ArrayHandler
 * @package Mix\WebSocket\Session
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class ArrayHandler extends AbstractComponent implements HandlerInterface
{

    /**
     * @var \Mix\WebSocket\Session\WebSocketSession
     */
    public $parent;

    /**
     * WebSocket会话数据
     * @var array
     */
    protected $_session = [];

    /**
     * 获取
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        $session = &$this->_session;
        $fd      = $this->parent->fd;
        if (is_null($key)) {
            return $session[$fd] ?? [];
        }
        return $session[$fd][$key] ?? null;
    }

    /**
     * 设置
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $session            = &$this->_session;
        $fd                 = $this->parent->fd;
        $session[$fd][$key] = $value;
        return true;
    }

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        $session = &$this->_session;
        $fd      = $this->parent->fd;
        unset($session[$fd][$key]);
        return true;
    }

    /**
     * 清除
     * @return bool
     */
    public function clear()
    {
        $session = &$this->_session;
        $fd      = $this->parent->fd;
        unset($session[$fd]);
        return true;
    }

    /**
     * 判断是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $session = &$this->_session;
        $fd      = $this->parent->fd;
        return isset($session[$fd][$key]) ? true : false;
    }

}
