<?php

$nodeConfig = require APPPATH . '/config/param/nodejs.php';

$config = [
    'NODE_PORT' => $nodeConfig['NODE_PORT'],
    'HOST' => $nodeConfig['HOST'],
    'JWT_SECRET' => $nodeConfig['JWT_SECRET'],
    'REDIS_CHANNEL_NAME' =>  $nodeConfig['REDIS_CHANNEL_NAME'],
    'REDIS_PORT' => $nodeConfig['REDIS_PORT'],
];

