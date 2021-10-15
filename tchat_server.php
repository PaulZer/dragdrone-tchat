<?php
use Ratchet\Server\IoServer;
use App\Tchat;

    require dirname(__DIR__) . '/vendor/autoload.php';

    $server = IoServer::factory(
        new Tchat(),
        8080
    );

    $server->run();