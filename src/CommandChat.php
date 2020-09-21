<?php

namespace App;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class CommandChat extends Command
{
    protected function configure()
    {
        $this
            ->setName('afsy:app:chat-server')
            ->setDescription('Start chat server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo "Lancement ...\n";
        $server = IoServer::factory(
            new HttpServer(new WsServer(new Chat())),
            82,
            //'192.168.43.80'
            /*8080,*/
            '192.168.1.36'
        );
        $server->run();
        echo "ok\n";
    }
}