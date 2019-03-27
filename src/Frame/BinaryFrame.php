<?php

namespace Mix\WebSocket\Frame;

use Mix\Core\Bean\ObjectInterface;
use Mix\Core\Bean\ObjectTrait;
use \Swoole\WebSocket\Frame;

/**
 * Class BinaryFrame
 * @package Mix\WebSocket\Frame
 * @author liu,jian <coder.keda@gmail.com>
 */
class BinaryFrame extends Frame implements ObjectInterface
{
    
    use ObjectTrait;

    /**
     * @var int
     */
    public $opcode = WEBSOCKET_OPCODE_BINARY;

    /**
     * @var bool
     */
    public $finish = true;

    /**
     * @var string
     */
    public $data = '';

}
