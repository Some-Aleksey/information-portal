<?php
return [
    'db' => [
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=information_portal;host=localhost:3306',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Zend\Db\Adapter\Adapter'
            => 'Zend\Db\Adapter\AdapterServiceFactory',
        ],
    ],
    'session_config' => [
      'cookie_lifetime' => 60*60*1,
      'gc_maxlifetime' => 60*60*10,
    ],
    'session_storage' => [
        'type' => \Zend\Session\Storage\SessionArrayStorage::class,
    ]
];
