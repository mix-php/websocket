<?php

namespace Mix\WebSocket;

use Mix\Core\BeanObject;

/**
 * Controller类
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class Controller extends BeanObject
{

    /**
     * 服务
     * @var \Swoole\WebSocket\Server
     */
    public $server;

    // 文件描述符
    public $fd;

}
