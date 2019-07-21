<?php

namespace Shevaua\DB\Mysql\Migrations\Mysql;

use Shevaua\DB\Mysql\Migrations\Mysql\Helper;

trait SchemaManipulator
{

    /**
     * Check if table already exists
     * @param string $name
     * @return boolean
     */
    public function isTableExist(string $name): bool
    {
        $query = Helper::querySearchTable($this->driver->escape($name));

        /** @var mysqli_result */
        $dataset = $this->driver->run($query);

        /** @var bool */
        $result = $dataset->num_rows > 0;

        $dataset->free();

        return $result;
    }

    /**
     * Create table
     * @param string $name
     * @param array $cols
     * @param array $pk
     * @param string $engine
     * @return void
     */
    public function createTable(
        string $name,
        array $cols,
        array $pk = ['id'],
        string $engine = 'InnoDB'
    ): void {
        $query = 'create table `'.$this->driver->escape($name).'` (';
        foreach ($cols as $column => $type) {
            $query .= '`' . $this->driver->escape($column) . '` '
                . $type . ', ';
        }
        if ($pk) {
            $query .= 'PRIMARY KEY (`' . implode('`,`', $pk) . '`), ';
        }
        $query = rtrim($query, ', ');
        $query .= ') engine = ' . $engine;
        $this->driver->run($query);
    }

    /**
     * Drop the table
     * @param string $name
     * @return void
     */
    public function dropTable(string $name): void
    {
        $query = 'drop table `'.$this->driver->escape($name).'`';
        $this->driver->run($query);
    }

    /**
     * Check if index exist by name
     * @param string $table
     * @param string $index
     * @return boolean
     */
    public function isIndexExist(string $table, string $index): bool
    {
        $query = Helper::queryListIndexes($table);

        /** @var mysqli_result */
        $dataset = $this->driver->run($query);

        $result = false;

        while ($row = $dataset->fetch_assoc()) {
            if ($row['Key_name'] == $index) {
                $result = true;
                break;
            }
        }

        $dataset->free();

        return $result;
    }

    /**
     * Create index
     * @param string $table
     * @param string $name
     * @param array $columns
     * @return void
     */
    public function addIndex(
        string $table,
        string $name,
        array $columns = []
    ): void {
        $query = 'alter table `'.$this->driver->escape($table).'` ';
        $query .= 'add index `'.$this->driver->escape($name).'` ';
        $query .= '(`'.implode('`,`', $columns ?: [$name]).'`)';
        $this->driver->run($query);
    }

    /**
     * Drop Index
     * @param string $table
     * @param string $name
     * @return void
     */
    public function dropIndex(string $table, string $name): void
    {
        $query = 'alter table `'.$this->driver->escape($table).'` ';
        $query .= 'drop index `'.$this->driver->escape($name).'`';
        $this->driver->run($query);
    }

    /**
     * @param string $table
     * @param string $index
     * @param string[] $columns
     * @return boolean
     */
    public function isIndexEqualTo(
        string $table,
        string $index,
        array $columns
    ): bool {
        $query = Helper::queryListIndexes($table);

        /** @var mysqli_result */
        $dataset = $this->driver->run($query);

        $currentColumns = [];

        while ($row = $dataset->fetch_assoc()) {
            if ($row['Key_name'] == $index) {
                $currentColumns[] = $row['Column_name'];
            }
        }

        $dataset->free();

        return $currentColumns === $columns;
    }

    /**
     * @param string $table
     * @param string $column
     * @return boolean
     */
    public function isColumnExist(string $table, string $column): bool
    {
        $query = Helper::queryListColumns($table, $column);

        /** @var mysqli_result */
        $dataset = $this->driver->run($query);

        /** @var bool */
        $result = $dataset->num_rows > 0;

        $dataset->free();

        return $result;
    }

    /**
     * @param string $table
     * @param string $name
     * @param string $type
     * @param string $after
     * @return void
     */
    public function addColumn(
        string $table,
        string $name,
        string $type,
        string $after = null
    ): void {
        $query = 'alter table `'.$this->driver->escape($table).'` ';
        $query .= 'add column `'.$this->driver->escape($name).'` ';
        $query .= $type . ' ';
        if ($after) {
            $query .= 'after `'.$this->driver->escape($after).'`';
        }
        $this->driver->run($query);
    }

    /**
     * @param string $table
     * @param string $name
     * @return void
     */
    public function dropColumn(string $table, string $name): void
    {
        $query = 'alter table `'.$this->driver->escape($table).'` ';
        $query .= 'drop column `'.$this->driver->escape($name).'` ';
        $this->driver->run($query);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function isFKExist(string $name): bool
    {
        $query = Helper::queryListFK($name);

        /** @var mysqli_result */
        $dataset = $this->driver->run($query);

        /** @var bool */
        $result = $dataset->num_rows > 0;

        $dataset->free();

        return $result;
    }

    /**
     * @param string $table
     * @param string $name
     * @param string[] $columns
     * @param string $refTable
     * @param string $refColumn
     * @param string $onUpdate
     * @param string $onDelete
     * @return void
     */
    public function addFK(
        string $table,
        string $name,
        array $columns,
        string $refTable,
        array $refColumns,
        string $onUpdate = 'cascade',
        string $onDelete = 'cascade'
    ): void {
        $query = 'alter table `'.$this->driver->escape($table).'` ';
        $query .= 'add constraint `'.$this->driver->escape($name).'` ';
        $query .= 'foreign key (`'.implode('`,`', $columns).'`) ';
        $query .= 'references `'.$this->driver->escape($refTable)
            . '` (`'.implode('`,`', $refColumns).'`) ';
        $query .= 'on delete '.$onDelete. ' ';
        $query .= 'on update '.$onUpdate. ' ';
        $this->driver->run($query);
    }

    /**
     * @param string $table
     * @param string $name
     * @return void
     */
    public function dropFK(string $table, string $name): void
    {
        $query = 'alter table `'.$this->driver->escape($table).'` ';
        $query .= 'drop foreign key `'.$this->driver->escape($name).'`';
        $this->driver->run($query);
    }

}
