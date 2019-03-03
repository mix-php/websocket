<?php

namespace Mix\WebSocket\Message;

use Mix\Core\Bean\ObjectTrait;
use Mix\Core\Bean\ObjectInterface;

/**
 * Class Frame
 * @package Mix\WebSocket\Message
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class Frame extends \Swoole\WebSocket\Frame implements ObjectInterface
{

    use ObjectTrait;

}
