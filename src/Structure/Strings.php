<?php

namespace SwooleRedis\Structure;

use ArrayIterator;
use Swoole\Redis\Server;
use SwooleRedis\Response;
use SwooleRedis\Service;

class Strings extends AbstractStructure
{
    public static $commands = ['get', 'set'];

    public $iterator = null;

    public function __construct()
    {
        $this->iterator = new ArrayIterator();
    }

    public function get($fd, $data)
    {
        if (count($data) !== 1) {
            return Response::wrongNumber(__METHOD__);
        }

        if (Service::$keys->offsetExists($data[0])) {
            return Response::string(Service::$keys->offsetGet($data[0]));
        } else {
            return Response::nil();
        }
    }

    public function set($fd, $data)
    {
        if (count($data) === 2) {
            Service::$keys->offsetSet($data[0], $data[1]);

            return Server::format(Server::STATUS, 'OK');
        } else {
            return Response::wrongNumber(__METHOD__);
        }
    }
}
