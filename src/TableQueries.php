<?php

namespace Shevaua\DB\Mysql\Migrations;

interface TableQueries
{

    /**
     * @param string $name
     * @return boolean
     */
    public function isTableExist(string $name): bool;

    /**
     * @param string $name
     * @param string[] $cols
     * @param string[] $pk
     * @param string $engine
     * @return void
     */
    public function createTable(
        string $name,
        array $cols,
        array $pk = ['id'],
        string $engine = 'InnoDB'
    ): void;

    /**
     * @param string $name
     * @return void
     */
    public function dropTable(string $name): void;

}
