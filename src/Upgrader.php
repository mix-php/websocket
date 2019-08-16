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
     * @var ConnectionManager
     */
    public $connectionManager;

    /**
     * Upgrader constructor.
     */
    public function __construct()
    {
        $this->connectionManager = new ConnectionManager();
    }

    /**
     * Upgrade
     * @param ServerRequest $request
     * @param Response $response
     * @return Connection
     */
    public function Upgrade(ServerRequest $request, Response $response)
    {
        $swooleRequest  = $request->getSwooleRequest();
        $swooleResponse = $response->getSwooleResponse();
        $swooleResponse->upgrade();
        /** @var ConnectionManager $connectionManager */
        $connection = new Connection($swooleResponse);
        $this->connectionManager->add($swooleRequest->fd, $connection);
        return $connection;
    }

    /**
     * Destroy
     */
    public function destroy()
    {
        $this->connectionManager->closeAll();
    }

}
