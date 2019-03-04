<?php

namespace Mix\WebSocket\Message;

use Mix\Core\Bean\ObjectTrait;
use Mix\Core\Bean\ObjectInterface;

/**
 * Class CloseFrame
 * @package Mix\WebSocket\Message
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class CloseFrame extends Frame
{

    /**
     * @var int
     */
    public $code;

    /**
     * @var string
     */
    public $reason;

}
