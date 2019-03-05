<?php

namespace Mix\WebSocket\Registry;

use Mix\Http\Message\Request;
use Mix\WebSocket\Frame;

/**
 * Interface HandlerInterface
 * @package Mix\WebSocket\Registry
 * @author LIUJIAN <coder.keda@gmail.com>
 */
interface HandlerInterface
{

    /**
     * 开启连接
     * @param Request $request
     * @return void
     */
    public function open(Request $request);

    /**
     * 处理消息
     * @param Frame $frame
     * @return void
     */
    public function message(Frame $frame);

    /**
     * 连接关闭
     * @return void
     */
    public function close();

}
