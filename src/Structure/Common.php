<?php

namespace Sedis\Structure;

use Sedis\Response;
use Sedis\Service;

class Common extends AbstractStructure
{
    public static $commands = ['del', 'ping'];

    public function del($fd, $data)
    {
        if (count($data) === 0) {
            return Response::wrongNumber(__METHOD__);
        }

        $count = 0;

        array_map(function ($key) use (&$count) {
            if (Service::$keys->offsetExists($key)) {
                Service::$keys->offsetUnset($key);
                $count++;
            }
        }, $data);

        return Response::int($count);
    }

    public function ping($fd, $data)
    {
        if (count($data) > 1) {
            return Response::wrongNumber(__METHOD__);
        }

        $message = isset($data[0]) ? $data[0] : 'PONG';

        return Response::string($message);
    }
}
