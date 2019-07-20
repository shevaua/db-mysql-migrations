<?php

namespace Shevaua\DB\Mysql\Migrations;

class Config
{
    const DEFAULT_TABLE = 'migration';

    /** @var string */
    protected $table = self::DEFAULT_TABLE;

    /** @var string */
    protected $folder = '';

    /** @var bool */
    protected $rollbackOnError = false;

    /** @var string */
    protected $db = '';

    /** @var string */
    protected $user = '';

    /** @var string */
    protected $passwd = '';

    /** @var string */
    protected $host = '127.0.0.1';

    /** @var string */
    protected $port = '3306';

    /** @var string */
    protected $socket = '';

    /** @var array */
    protected $ignored = [];

    /**
     * @param array $params
     * @return self
     */
    public function __construct(array $params)
    {
        $fields = [
            'table',
            'folder',
            'db',
            'user',
            'passwd',
            'host',
            'port',
            'socket'
        ];
        foreach ($fields as $field) {
            if (isset($params[$field])) {
                $this->$field = (string)$params[$field];
            }
        }
        if (isset($params['ignore']) && is_array($params['ignore'])) {
            $this->ignored = $params['ignore'];
        }
        if (isset($params['rollback_on_error'])) {
            $this->rollbackOnError = (bool)$params['rollback_on_error'];
        }
    }

    /**
     * Get table name
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Get path to folder with migrations
     * @return string
     */
    public function getFolder(): string
    {
        return $this->folder;
    }

    /**
     * Get database name
     * @return string
     */
    public function getDB(): string
    {
        return $this->db;
    }

    /**
     * Get username for db connection
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * Get password for db connection
     * @return string
     */
    public function getPasswd(): string
    {
        return $this->passwd;
    }

    /**
     * Get hostname/IP address for db connection
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get port for db connection
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * Get socket for db connection
     * @return string
     */
    public function getSocket(): string
    {
        return $this->socket;
    }

    /**
     * Get list of ignored subpathes
     * @return string[]
     */
    public function getIgnoreList(): array
    {
        return $this->ignored;
    }

    /**
     * Get flag for rollbackOnError
     * @return bool
     */
    public function rollbackOnError(): bool
    {
        return $this->rollbackOnError;
    }

}
