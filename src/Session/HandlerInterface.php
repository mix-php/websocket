<?php

namespace Mix\WebSocket\Session;

/**
 * Interface HandlerInterface
 * @package Mix\WebSocket\Session
 * @author LIUJIAN <coder.keda@gmail.com>
 */
interface HandlerInterface
{

    /**
     * 获取
     * @param null $key
     * @return mixed
     */
    public function get($key = null);

    /**
     * 设置
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value);

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key);

    /**
     * 清除
     * @return bool
     */
    public function clear();

    /**
     * 判断是否存在
     * @param $key
     * @return bool
     */
    public function has($key);

}
