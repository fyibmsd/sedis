<?php

namespace SwooleRedis;

use ArrayIterator;
use Swoole\Redis\Server;
use SwooleRedis\Structure\AbstractStructure;

class Service
{
    private $modules = [];

    private $server = null;

    public static $keys = null;

    public function __construct($host, $port)
    {
        $this->server = new Server($host, $port, SWOOLE_BASE);
        self::$keys = new ArrayIterator();

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

    public function start()
    {
        return $this->server->start();
    }
}
