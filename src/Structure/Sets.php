<?php

namespace SwooleRedis\Structure;

use ArrayIterator;
use SwooleRedis\Response;
use SwooleRedis\Service;

class Sets extends AbstractStructure
{
    public static $commands = ['sadd', 'scard'];

    //'scard', 'sismember', 'smembers', 'spop'];

    public function sadd($fd, $data)
    {
        if (count($data) !== 2) {
            return Response::invalidArguments(__METHOD__);
        }

        if (!Service::$keys->offsetExists($data[0])) {
            Service::$keys[$data[0]] = new ArrayIterator();
        }

        $instance = Service::$keys[$data[0]];

        $count = $instance->count();

        $instance[$data[1]] = true;

        return Response::int($instance->count() - $count);
    }

    public function scard($fd, $data)
    {
        $instance = Service::$keys[$data[0]];

        return Response::int($instance->count());
    }
}
