<?php

namespace Shevaua\DB\Mysql\Migrations\Database;

use Shevaua\DB\Mysql\Migrations\ColumnQueries;
use Shevaua\DB\Mysql\Migrations\Config;
use Shevaua\DB\Mysql\Migrations\Configuration;
use Shevaua\DB\Mysql\Migrations\DataQueries;
use Shevaua\DB\Mysql\Migrations\IndexQueries;
use Shevaua\DB\Mysql\Migrations\ForeignKeyQueries;
use Shevaua\DB\Mysql\Migrations\TableQueries;
use Shevaua\DB\Mysql\Migrations\Mysql\DataManipulator;
use Shevaua\DB\Mysql\Migrations\Mysql\Driver;
use Shevaua\DB\Mysql\Migrations\Mysql\DriverContainer;
use Shevaua\DB\Mysql\Migrations\Mysql\SchemaManipulator;

class DBDriver implements
    ColumnQueries,
    IndexQueries,
    ForeignKeyQueries,
    TableQueries,
    DataQueries
{
    use Configuration;
    use DriverContainer;
    use SchemaManipulator;
    use DataManipulator;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);
        $this->setDriver(new Driver($config));
    }

    /**
     * Get list of migrations from database
     *
     * @param string $order
     * @param integer $group
     * @return array
     */
    public function getMigrations(string $order = 'asc', int $group = 0): array
    {
        $table = $this->config->getTable();
        if (!$this->isTableExist($table)) {
            return [];
        }
        $result = [];
        $rows = $this->select(
            $table,
            ['id', 'name'],
            $group > 0 ? ['group' => $group]: [],
            in_array($order, ['asc', 'desc']) ? ['id' => $order] : []
        );
        foreach ($rows as $row) {
            $result[$row['id']] = $row['name'];
        }
        return $result;
    }

    /**
     * Register migration within group
     *
     * @param string $name
     * @return void
     */
    public function register(string $name, int $group): void
    {
        $table = $this->config->getTable();
        $this->insert($table, [
            'name' => $name,
            'group' => $group,
        ]);
    }

    /**
     * Unregister migration
     *
     * @param string $name
     * @return void
     */
    public function unregister(string $name): void
    {
        $table = $this->config->getTable();
        $this->delete($table, ['name' => $name,]);
    }

    /**
     * Get last group for migrations
     * @return int
     */
    public function getLastGroup(): int
    {
        $table = $this->config->getTable();
        $rows = $this->select($table, 'max(`group`) as max_group');
        return (int)$rows[0]['max_group'];
    }

    /**
     * Set mode for autocommit
     *
     * @param boolean $value
     * @return void
     */
    public function setAutocommit(bool $value): void
    {
        $this->driver->setAutocommit($value);
    }

    /**
     * Begin the transaction
     *
     * @return void
     */
    public function startTransaction(): void
    {
        $this->driver->start();
    }

    /**
     * Stop the transaction.
     * Depend on the $success flag, it will commit or rollback
     *
     * @param boolean $value
     * @return void
     */
    public function stopTransaction(bool $withCommit = true): void
    {
        $this->driver->stop($withCommit);
    }

}
