<?php

return [
    /**
     * Table for storing migration history
     */
    'table' => 'migration',

    /**
     * DB connection parameters
     */
    'host' => DB_HOST,
    'port' => DB_PORT,
    'socket' => DB_SOCKET,

    /**
     * DB credentials
     */
    'user' => DB_USER,
    'passwd' => DB_PASS,
    'db' => DB_NAME,

    /**
     * Rollback last migration on error
     */
    'rollback_on_error' => true,

    /**
     * Folder for storing migrations
     */
    'folder' => dirname(__DIR__) . '/migrations',

    /**
     * List of ignored migrations
     */
    'ignore' => [
    ],

];
