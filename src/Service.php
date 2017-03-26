<?php

namespace Sedis;

use ArrayIterator;
use Swoole\Redis\Server;

class Service
{
    const SEDIS_VERSION = 'v1.0.0';

    private $modules = [];

    private $server = null;

    public static $keys = null;

    private $host;

    private $port;

    public function __construct($host, $port)
    {
        $this->server = new Server($host, $port, SWOOLE_BASE);
        $this->host   = $host;
        $this->port   = $port;
        self::$keys   = new ArrayIterator();

        $this->server->set([
            'server'  => [
                'host' => $host,
                'port' => $port
            ],
            'setting' => [
                'worker_num' => 1
            ]
        ]);
    }

    public function add($middleware)
    {
        if (!array_key_exists($middleware, $this->modules)) {
            $this->modules[$middleware] = new $middleware;
        }

        $middleware = $this->modules[$middleware];

        foreach ($middleware::$commands as $command) {
            $this->server->setHandler($command, [$middleware, $command]);
        }
    }

    public function addModules(array $modules)
    {
        foreach ($modules as $module) $this->add($module);
    }

    public function start()
    {
        printf("Swoole Redis Server running at tcp://%s:%s\n", $this->host, $this->port);

        return $this->server->start();
    }
}
