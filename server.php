<?php

use SwooleRedis\Service;

require __DIR__ . '/vendor/autoload.php';

$cfg = require __DIR__ . '/config/server.php';

$rds = new Service($cfg['host'], $cfg['port']);

$rds->add(\SwooleRedis\Structure\Strings::class);

$rds->add(\SwooleRedis\Structure\Lists::class);

$rds->add(\SwooleRedis\Structure\Sets::class);

$rds->start();
