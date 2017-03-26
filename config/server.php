<?php

use Sedis\Structure\Common;
use Sedis\Structure\Lists;
use Sedis\Structure\Sets;
use Sedis\Structure\Strings;

return [
    'host'    => '127.0.0.1',
    'port'    => 7001,
    'modules' => [
        Common::class,
        Strings::class,
        Lists::class,
        Sets::class
    ]
];
