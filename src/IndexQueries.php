<?php

namespace Shevaua\DB\Mysql\Migrations;

interface IndexQueries
{

    /**
     * @param string $table
     * @param string $index
     * @return boolean
     */
    public function isIndexExist(string $table, string $index): bool;

    /**
     * @param string $table
     * @param string $name
     * @param string[] $columns
     * @return void
     */
    public function addIndex(
        string $table,
        string $name,
        array $columns = []
    ): void;

    /**
     * @param string $table
     * @param string $name
     * @return void
     */
    public function dropIndex(string $table, string $name): void;

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
    ): bool;

}
