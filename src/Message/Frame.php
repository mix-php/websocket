<?php

namespace Mix\WebSocket\Message;

use Mix\Core\Bean\ObjectTrait;
use Mix\Core\Bean\ObjectInterface;

/**
 * Class Frame
 * @package Mix\WebSocket\Message
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class Frame implements ObjectInterface
{

    use ObjectTrait;

    /**
     * @var int
     */
    public $fd;

    /**
     * @var bool
     */
    public $finish;

    /**
     * @var string
     */
    public $opcode;

    /**
     * @var string
     */
    public $data;

}
