<?php

return [
    'table' => 'migration_case1',

    'host' => DB_HOST,
    'port' => DB_PORT,
    'socket' => DB_SOCKET,
    'user' => DB_USER,
    'passwd' => DB_PASS,
    'db' => DB_NAME,

    'rollback_on_error' => true,

    'folder' => dirname(dirname(__DIR__)) . '/migrations/tests/case1',
    'ignore' => [],
];
