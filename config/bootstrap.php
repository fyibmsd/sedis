<?php

use Sedis\Response;
use Sedis\Service;

define('SEDIS_ROOT_DIR', realpath(__DIR__ . '/../'));

class Bootstrap
{
    /**
     * @var $sedis Service
     */
    protected static $sedis = null;

    /**
     * @var $config array
     */
    protected static $config;

    private static $options = 'hvH:p:';

    private static $longopts = ['help', 'version', 'host', 'port'];

    private static $usage = <<<EOF
    Usage:
        ./sedis-server [options] -- [args..]
        -h [--help] print usage
        -p [--port] tcp port number to listen on (default: 7001) 
        -H [--host] server host \n
EOF;

    private static function createServer()
    {
        if (self::$sedis === null)
            self::$sedis = new Service(self::$config['host'], self::$config['port']);

        return self::$sedis;
    }

    public static function paramsHelp(array $opt)
    {
        if (empty($opt) || isset($opt['h']))
            Response::log(self::$usage, 0);
    }

    public static function paramsVersion(array $opt)
    {
        if (isset($opt['v']) || isset($opt['version']))
            Response::log('Sedis Server ' . Service::SEDIS_VERSION, 0);
    }

    public static function paramsHost(array $opt)
    {
        if (isset($opt['H']) || isset($opt['host']))
            self::$config['host'] = $opt['H'] ?? $opt['host'];
    }

    public static function paramsPort(array $opt)
    {
        if (isset($opt['p']) || isset($opt['port']))
            self::$config['port'] = $opt['p'] ?? $opt['port'];

        if (self::$config['port'] > 65535 || self::$config['port'] < 1024)
            Response::log('Params error: tcp port should be between 1024 and 65535', 1);
    }

    public static function run()
    {
        /**
         * composer autoloader
         */
        require SEDIS_ROOT_DIR . '/vendor/autoload.php';

        /**
         * common configuration
         */
        self::$config = require SEDIS_ROOT_DIR . '/config/server.php';

        $opt = getopt(self::$options, self::$longopts);

        /**
         * params handler
         */
        self::paramsHelp($opt);
        self::paramsVersion($opt);
        self::paramsHost($opt);
        self::paramsPort($opt);

        /**
         * init sedis
         */
        self::createServer();

        return self::$sedis->start();
    }
}