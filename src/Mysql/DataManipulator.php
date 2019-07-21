<?php

namespace Shevaua\DB\Mysql\Migrations\Mysql;

trait DataManipulator
{

    /**
     * Select records from table
     * @param string $table
     * @param string|array $columns
     * @param string[] $where
     * @param string[] $order
     * @return array
     */
    public function select(
        string $table,
        $columns = [],
        array $where = [],
        array $order = []
    ): array {
        $result = [];
        $query = 'select '."\n";
        if ($columns) {
            if (is_array($columns)) {
                foreach ($columns as $column) {
                    $query .= '  `'.$this->driver->escape($column).'`,' . "\n";
                }
                $query = rtrim($query, ",\n")."\n";
            } else {
                $query .= '  ' . $columns . "\n";
            }
        } else {
            $query .= '  `'.$this->driver->escape($table).'`.*' . "\n";
        }
        $query .= 'from `'.$this->driver->escape($table)."`\n";

        if ($where) {
            $query .= 'where'. "\n";
            foreach ($where as $column => $value) {
                $query .= '  `'.$this->driver->escape($column).'`=';
                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                } elseif (is_string($value)) {
                    $value = '\''.$this->driver->escape($value).'\'';
                }
                $query .= $value . " and\n";
            }
            $query = rtrim($query, "and\n") . "\n";
        }

        if ($order) {
            $query .= 'order by' . "\n";
            foreach ($order as $column => $direction) {
                $query .= '`' . $this->driver->escape($column) . '` '
                    . $direction . ",\n";
            }
            $query = rtrim($query, ",\n") . "\n";
        }

        $dataset = $this->driver->run($query);

        while ($row = $dataset->fetch_assoc()) {
            $result[] = $row;
        }

        $dataset->free();

        return $result;
    }

    /**
     * @param string $table
     * @param array $data
     * @return int
     */
    public function insert(
        string $table,
        array $data
    ): int {
        $query = 'insert into `'.$this->driver->escape($table).'`' . "\n";
        $query .= 'set' . "\n";
        foreach ($data as $column => $value) {
            $query .= '  `' . $this->driver->escape($column) . '` = ';
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } elseif (is_string($value)) {
                $value = '\''.$this->driver->escape($value).'\'';
            }
            $query .= $value . ",\n";
        }
        $query = rtrim($query, ",\n") . "\n";
        $this->driver->run($query);
        return $this->driver->getLastId();
    }

    /**
     * @param string $table
     * @param string[] $where
     * @return void
     */
    public function delete(
        string $table,
        array $where
    ): void {
        $query = 'delete from `'.$this->driver->escape($table).'`' . "\n";
        if ($where) {
            $query .= 'where'. "\n";
            foreach ($where as $column => $value) {
                $query .= '  `'.$this->driver->escape($column).'`=';
                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                } elseif (is_string($value)) {
                    $value = '\''.$this->driver->escape($value).'\'';
                }
                $query .= $value . " and\n";
            }
            $query = rtrim($query, "and\n") . "\n";
        }
        $this->driver->run($query);
    }

}
