<?php

return [
    'table' => 'migration_case2',

    'host' => DB_HOST,
    'port' => DB_PORT,
    'socket' => DB_SOCKET,
    'user' => DB_USER,
    'passwd' => DB_PASS,
    'db' => DB_NAME,

    'rollback_on_error' => false,

    'folder' => dirname(dirname(__DIR__)) . '/migrations/tests/case2',
    'ignore' => [],
];
