<?php

namespace Mix\WebSocket;

use Mix\Http\Message\Response;
use Mix\Http\Message\ServerRequest;

/**
 * Class Upgrader
 * @package Mix\WebSocket
 * @author liu,jian <coder.keda@gmail.com>
 */
class Upgrader
{

    /**
     * Upgrade
     * @param ServerRequest $request
     * @param Response $response
     * @return Connection
     */
    public function Upgrade(ServerRequest $request, Response $response)
    {
        $swooleResponse = $response->swooleResponse;
        $swooleResponse->upgrade();
        return new Connection($response);
    }

}
