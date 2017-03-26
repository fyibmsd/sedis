<?php

namespace Sedis\Structure;

use SplDoublyLinkedList;
use Sedis\Response;
use Sedis\Service;

class Lists extends AbstractStructure
{
    public static $commands = ['lpush', 'lpop', 'rpush', 'rpop', 'llen'];

    public function lpush($fd, $data)
    {
        if (!Service::$keys->offsetExists($data[0])) {
            Service::$keys[$data[0]] = new SplDoublyLinkedList();
        }

        $instance = Service::$keys[$data[0]];

        $instance->unshift($data[1]);

        return Response::int($instance->count());
    }

    public function lpop($fd, $data)
    {
        if (!Service::$keys->offsetExists($data[0])) {
            return Response::wrongNumber(__METHOD__);
        }

        return Response::string(Service::$keys[$data[0]]->shift());
    }

    public function rpush($fd, $data)
    {
        if (!Service::$keys->offsetExists($data[0])) {
            Service::$keys[$data[0]] = new SplDoublyLinkedList();
        }

        Service::$keys[$data[0]]->push($data[1]);

        return Response::int(Service::$keys[$data[0]]->count());
    }

    public function rpop($fd, $data)
    {
        if (!Service::$keys->offsetExists($data[0])) {
            return Response::wrongNumber(__METHOD__);
        }

        return Response::string(Service::$keys[$data[0]]->pop());
    }

    public function llen($fd, $data)
    {
        if (!isset($data[0])) {
            return Response::wrongNumber(__METHOD__);
        }

        $len = Service::$keys->offsetExists($data[0]) ? Service::$keys[$data[0]]->count() : 0;

        return Response::int($len);
    }
}
