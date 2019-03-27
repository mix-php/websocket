<?php

namespace Mix\WebSocket\Frame;

use Mix\Core\Bean\ObjectInterface;
use Mix\Core\Bean\ObjectTrait;

/**
 * Class CloseFrame
 * @package Mix\WebSocket\Frame
 * @author liu,jian <coder.keda@gmail.com>
 */
class CloseFrame extends \Swoole\WebSocket\CloseFrame implements ObjectInterface
{

    use ObjectTrait;

    /**
     * @var int
     */
    public $opcode = 8;

    /**
     * @var bool
     */
    public $finish = true;

    /**
     * @var string
     */
    public $data = '';

    /**
     * @var int
     */
    public $code = 1000;

    /**
     * @var string
     */
    public $reason = '';

}
