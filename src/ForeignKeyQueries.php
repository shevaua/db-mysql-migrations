<?php

namespace Shevaua\DB\Mysql\Migrations;

interface ForeignKeyQueries
{

    /**
     * @param string $name
     * @return boolean
     */
    public function isFKExist(string $name): bool;

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
    ): void;

    /**
     * @param string $table
     * @param string $name
     * @return void
     */
    public function dropFK(string $table, string $name): void;

}
