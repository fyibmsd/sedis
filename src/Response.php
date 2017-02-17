<?php

namespace SwooleRedis;

use Swoole\Redis\Server;

class Response
{
    public static function string(string $string)
    {
        return Server::format(Server::STRING, $string);
    }

    public static function int(int $number)
    {
        return Server::format(Server::INT, $number);
    }

    public static function set(array $data)
    {
        return Server::format(Server::SET, $data);
    }

    public static function map(array $data)
    {
        return Server::format(Server::MAP, $data);
    }

    public static function nil()
    {
        return Server::format(Server::NIL);
    }

    public static function wrongType()
    {
        return self::error('WRONGTYPE Operation against a key holding the wrong kind of value');
    }

    public static function wrongNumber(string $method)
    {
        $message = sprintf('ERR wrong number of arguments for \'%s\' command', explode('::', $method)[1]);

        return self::error($message);
    }

    public static function error(string $message)
    {
        return Server::format(Server::ERROR, $message);
    }
}
