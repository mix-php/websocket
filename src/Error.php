<?php

namespace Mix\WebSocket;

use Mix\Core\Component\AbstractComponent;
use Mix\Core\Component\ComponentInterface;
use Mix\Helper\JsonHelper;
use Mix\WebSocket\Frame\TextFrame;

/**
 * Class Error
 * @package Mix\WebSocket
 * @author liu,jian <coder.keda@gmail.com>
 */
class Error extends AbstractComponent
{

    /**
     * 协程模式
     * @var int
     */
    const COROUTINE_MODE = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 错误级别
     * @var int
     */
    public $level = E_ALL;

    /**
     * 异常处理
     * @param $e
     */
    public function handleException($e)
    {
        // 错误参数定义
        $statusCode = $e instanceof \Mix\Exception\NotFoundException ? 404 : 500;
        $errors     = [
            'status'  => $statusCode,
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'type'    => get_class($e),
            'trace'   => $e->getTraceAsString(),
        ];
        // 日志处理
        if (!($e instanceof \Mix\Exception\NotFoundException)) {
            static::log($errors);
        }
        // 发送客户端
        static::send($errors);
        // 关闭连接
        static::close($errors);
    }

    /**
     * 写入日志
     * @param $errors
     */
    protected static function log($errors)
    {
        // 构造消息
        $message = "{message}\n[code] {code} [type] {type}\n[file] {file} [line] {line}\n[trace] {trace}";
        if (!\Mix::$app->appDebug) {
            $message = "{message} [{code}] {type} in {file} line {line}";
        }
        // 写入
        $level = \Mix\Core\Error::getLevel($errors['code']);
        switch ($level) {
            case 'error':
                \Mix::$app->log->error($message, $errors);
                break;
            case 'warning':
                \Mix::$app->log->warning($message, $errors);
                break;
            case 'notice':
                \Mix::$app->log->notice($message, $errors);
                break;
        }
    }

    /**
     * 发送客户端
     * @param $errors
     */
    protected static function send($errors)
    {
        if (!\Mix::$app->isRunning('ws')) {
            return;
        }
        $errors['trace'] = explode("\n", $errors['trace']);
        $statusCode      = $errors['status'];
        if (!\Mix::$app->appDebug) {
            if ($statusCode == 404) {
                $errors = [
                    'status'  => 404,
                    'message' => $errors['message'],
                ];
            }
            if ($statusCode == 500) {
                $errors = [
                    'status'  => 500,
                    'message' => '服务器内部错误',
                ];
            }
        }
        $frame = new TextFrame([
            'data' => JsonHelper::encode($errors),
        ]);
        \Mix::$app->ws->push($frame);
    }

    /**
     * 关闭连接
     * @param $errors
     */
    protected static function close($errors)
    {
        // 关闭握手
        if (\Mix::$app->isRunning('response')) {
            \Mix::$app->response->statusCode = $errors['status'];
            \Mix::$app->response->send();
        }
        // 关闭连接
        if (\Mix::$app->isRunning('ws')) {
            \Mix::$app->ws->disconnect();
        }
    }

}
