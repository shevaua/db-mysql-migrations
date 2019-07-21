<?php

namespace Shevaua\DB\Mysql\Migrations;

interface DataQueries
{

    /**
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
    ): array;

    /**
     * @param string $table
     * @param array $columns
     * @return integer
     */
    public function insert(
        string $table,
        array $data
    ): int;

    /**
     * @param string $table
     * @param string[] $where
     * @return void
     */
    public function delete(
        string $table,
        array $where
    ): void;

}
