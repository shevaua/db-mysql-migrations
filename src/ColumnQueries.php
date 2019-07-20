<?php

namespace Shevaua\DB\Mysql\Migrations;

interface ColumnQueries
{

    /**
     * @param string $table
     * @param string $column
     * @return boolean
     */
    public function isColumnExist(string $table, string $column): bool;

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
    ): void;

    /**
     * @param string $table
     * @param string $name
     * @return void
     */
    public function dropColumn(string $table, string $name): void;

}
