<?php

namespace Mix\WebSocket\Registry;

/**
 * Interface InterceptorInterface
 * @package Mix\WebSocket\Registry
 * @author LIUJIAN <coder.keda@gmail.com>
 */
interface InterceptorInterface
{

    /**
     * 握手
     * @return void
     */
    public function handshake();

}
