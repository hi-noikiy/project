<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '',
        'dbname'      => '91ns',
        'charset'     => 'utf8',
    ),
    'application' => array(
        'modelsDir'      => __DIR__ . '/../../apps/models/',
    )
));
