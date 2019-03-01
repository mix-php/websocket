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
     * 套接字描述符
     * @var int
     */
    protected $_fd;

    /**
     * WebSocket会话数据
     * @var array
     */
    protected $_session = [];

    /**
     * 设置套接字描述符
     * @param $fd
     * @return bool
     */
    public function setFildDescriptor($fd)
    {
        $this->_fd = $fd;
        return true;
    }

    /**
     * 获取
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        if (is_null($key)) {
            return $this->_session[$this->_fd] ?? [];
        }
        return $this->_session[$this->_fd][$key] ?? null;
    }

    /**
     * 设置
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $this->_session[$this->_fd][$key] = $value;
        return true;
    }

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        unset($this->_session[$this->_fd][$key]);
        return true;
    }

    /**
     * 清除
     * @return bool
     */
    public function clear()
    {
        $this->_session[$this->_fd] = [];
        unset($this->_session[$this->_fd]);
        return true;
    }

    /**
     * 判断是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->_session[$this->_fd][$key]) ? true : false;
    }

}
