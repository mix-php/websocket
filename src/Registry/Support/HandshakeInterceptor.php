<?php

namespace Mix\WebSocket\Registry\Support;

use Mix\Http\Message\Request;
use Mix\Http\Message\Response;

/**
 * Class HandshakeInterceptor
 * @package Mix\WebSocket\Registry\Support
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class HandshakeInterceptor
{

    /**
     * 握手
     * @param Request $request
     * @param Response $response
     */
    public function handshake(Request $request, Response $response)
    {
        $secWebSocketKey = $request->header('sec-websocket-key');
        $patten          = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
        if ($request->header('sec-websocket-version') != 13 || 0 === preg_match($patten, $secWebSocketKey) || 16 !== strlen(base64_decode($secWebSocketKey))) {
            $response->statusCode = 500;
            $response->send();
            return;
        }
        $key     = base64_encode(sha1(
            $secWebSocketKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
            true
        ));
        $headers = [
            'Upgrade'               => 'websocket',
            'Connection'            => 'Upgrade',
            'Sec-WebSocket-Accept'  => $key,
            'Sec-WebSocket-Version' => '13',
        ];
        // WebSocket connection to 'ws://127.0.0.1:9502/'
        // failed: Error during WebSocket handshake:
        // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
        if ($request->header('sec-websocket-protocol', false)) {
            $headers['Sec-WebSocket-Protocol'] = $request->header('sec-websocket-protocol');
        }
        foreach ($headers as $key => $val) {
            $response->setHeader($key, $val);
        }
        $response->statusCode = 101;
        $response->send();
    }

}
