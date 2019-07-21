<?php

namespace Shevaua\DB\Mysql\Migrations\Mysql;

use Shevaua\DB\Mysql\Migrations\Config;
use mysqli;
use Exception;

class Driver
{

    /** @var mysqli Connection */
    protected $connection;

    /** @var bool */
    protected $isTransactionActive = false;

    /** @var null|bool Auto Commit Mode */
    protected $autoCommit = null;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->setConnection(
            new mysqli(
                $config->getHost(),
                $config->getUser(),
                $config->getPasswd(),
                $config->getDB(),
                $config->getPort(),
                $config->getSocket()
            )
        );
    }

    /*
     * Need to rollback before close
     */
    public function __destruct()
    {
        if (
            is_bool($this->autoCommit)
            and !$this->autoCommit
            and $this->isTransactionActive
        ) {
            $this->stop(false);
        }
        $this->connection->close();
    }

    /**
     * Set mysqli connection
     *
     * @param mysqli $connection
     * @return self
     */
    public function setConnection(mysqli $connection): self
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * Get mysqli connection
     *
     * @return mysqli
     */
    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    /**
     * Run query using mysql connection
     *
     * @return bool|mysql_result
     * @throws QueryException
     */
    public function run($query)
    {
        $result = $this->connection->query($query);
        if ($result === false) {
            throw new QueryException(
                $query,
                $this->connection->errno,
                $this->connection->error
            );
        }
        return $result;
    }

    /**
     * Escape special characters
     *
     * @param string $str
     * @return string
     */
    public function escape(string $str): string
    {
        return $this->connection->escape_string($str);
    }

    /**
     * Get last insert id
     *
     * @return int
     */
    public function getLastId(): int
    {
        return (int)$this->connection->insert_id;
    }

    /**
     * Set mode for autocommit
     *
     * @param boolean $value
     * @return void
     * @throws Exception
     */
    public function setAutocommit(bool $value): void
    {
        if ($this->isTransactionActive) {
            throw new Exception(
                'Changing this flag during transaction is not allowed'
            );
        }
        $this->autoCommit = $value;
        $this->connection->autocommit($value);
    }

    /**
     * Begin the transaction
     *
     * @return void
     * @throws Exception
     */
    public function start(): void
    {
        if ($this->isTransactionActive) {
            throw new Exception('Transaction is already active');
        }
        if (
            is_bool($this->autoCommit)
            and $this->autoCommit
        ) {
            throw new Exception('Autocommit mode is enabled');
        }
        $this->connection->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $this->isTransactionActive = true;
    }

    /**
     * Stop the transaction.
     * Depend on the $success flag, it will commit or rollback
     *
     * @param boolean $success
     * @return void
     * @throws Exception
     */
    public function stop(bool $success = true): void
    {
        if (!$this->isTransactionActive) {
            throw new Exception('Transaction is not active');
        }

        if (
            is_bool($this->autoCommit)
            and $this->autoCommit
        ) {
            throw new Exception('Autocommit mode is enabled');
        }

        if ($success) {
            $this->connection->commit();
        } else {
            $this->connection->rollback();
        }

        $this->isTransactionActive = false;
    }

}
