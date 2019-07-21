<?php

namespace Shevaua\DB\Mysql\Migrations\Mysql;

abstract class Helper
{

    /**
     * Get query to search the table
     *
     * @param string $name
     * @return string
     */
    public static function querySearchTable(string $name): string
    {
        return "show tables like '$name'";
    }

    /**
     * Get query to list indexes
     *
     * @param string $table
     * @return string
     */
    public static function queryListIndexes(string $table): string
    {
        return "show indexes from `$table`";
    }

    /**
     * Get query to list columns within table
     *
     * @param string $table
     * @param string $column
     * @return string
     */
    public static function queryListColumns(
        string $table,
        string $column
    ): string {
        return "show columns from `$table` like '$column'";
    }

    /**
     * Get query to list FK
     *
     * @param string $name
     * @return string
     */
    public static function queryListFK(string $name): string
    {
        return "
            select distinct * 
            from information_schema.TABLE_CONSTRAINTS 
            where 
                CONSTRAINT_TYPE = 'FOREIGN KEY'
                and CONSTRAINT_NAME = '$name'
        ";
    }

}
