<?php

namespace Mix\WebSocket;

/**
 * Class Frame
 * @package Mix\WebSocket
 * @author liu,jian <coder.keda@gmail.com>
 */
class Frame
{

    /**
     * @var int
     */
    public $opcode;

    /**
     * @var string
     */
    public $data;

    /**
     * @var bool
     */
    public $finish;

    /**
     * @var int
     */
    public $code;

    /**
     * @var string
     */
    public $reason;

    /**
     * 帧
     * @var \Swoole\WebSocket\Frame
     */
    protected $_frame;

    /**
     * Frame constructor.
     * @param \Swoole\WebSocket\Frame $frame
     */
    public function __construct(\Swoole\WebSocket\Frame $frame)
    {
        // 设置帧
        $this->_frame = $frame;
        // 执行初始化
        $this->opcode = $frame->opcode;
        $this->data   = $frame->data;
        $this->finish = $frame->finish;
        if ($this->isCloseFrame()) {
            $this->code   = $frame->code;
            $this->reason = $frame->reason;
        }
    }

    /**
     * 前置处理事件
     */
    public function onBeforeInitialize()
    {
        // 移除设置组件状态
    }

    /**
     * 是否为数据帧
     * @return bool
     */
    public function isDataFrame()
    {
        return $this->_frame instanceof \Swoole\WebSocket\Frame ? true : false;
    }

    /**
     * 是否为文本帧
     * @return bool
     */
    public function isTextFrame()
    {
        return $this->opcode === WEBSOCKET_OPCODE_TEXT ? true : false;
    }

    /**
     * 是否为二进制帧
     * @return bool
     */
    public function isBinaryFrame()
    {
        return $this->opcode === WEBSOCKET_OPCODE_BINARY ? true : false;
    }

    /**
     * 是否为关闭帧
     * @return bool
     */
    public function isCloseFrame()
    {
        return $this->_frame instanceof \Swoole\WebSocket\CloseFrame ? true : false;
    }

    /**
     * 获取原始帧
     * @return \Swoole\WebSocket\Frame
     */
    public function getRawFrame()
    {
        return $this->_frame;
    }

}
