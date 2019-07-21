<?php

use PHPUnit\Framework\TestCase;

use Shevaua\DB\Mysql\Migrations\AbleToDowngrade;
use Shevaua\DB\Mysql\Migrations\AbleToUpgrade;
use Shevaua\DB\Mysql\Migrations\ColumnQueries;
use Shevaua\DB\Mysql\Migrations\Configuration;
use Shevaua\DB\Mysql\Migrations\ForeignKeyQueries;
use Shevaua\DB\Mysql\Migrations\IndexQueries;
use Shevaua\DB\Mysql\Migrations\TableQueries;
use Shevaua\DB\Mysql\Migrations\Mysql\DriverContainer;

class NamespaceTest extends TestCase
{

    /**
     * @return void
     */
    public function testAbleToDowngradeInterface(): void
    {
        $class = new class implements AbleToDowngrade
        {

            /**
             * Downgrade
             *
             * @return void
             */
            public function down(): void
            {
            }

        };
        $this->assertInstanceOf(AbleToDowngrade::class, $class);
    }

    /**
     * @return void
     */
    public function testAbleToUpgradeInterface(): void
    {
        $class = new class implements AbleToUpgrade
        {

            /**
             * Upgrade
             *
             * @return void
             */
            public function up(): void
            {
            }

        };
        $this->assertInstanceOf(AbleToUpgrade::class, $class);
    }

    /**
     * @return void
     */
    public function testColumnQueriesInterface(): void
    {
        $class = new class implements ColumnQueries
        {

            /***/
            public function isColumnExist(string $table, string $column): bool
            {
                return false;
            }

            /***/
            public function addColumn(
                string $table,
                string $name,
                string $type,
                string $after = null
            ): void {
            }

            /***/
            public function dropColumn(string $table, string $name): void
            {
            }

        };
        $this->assertInstanceOf(ColumnQueries::class, $class);
    }

    /**
     * @return void
     */
    public function testForeignKeyQueriesInterface(): void
    {
        $class = new class implements ForeignKeyQueries
        {

            /***/
            public function isFKExist(string $name): bool
            {
                return false;
            }

            /***/
            public function addFK(
                string $table,
                string $name,
                array $columns,
                string $refTable,
                array $refColumns,
                string $onUpdate = 'cascade',
                string $onDelete = 'cascade'
            ): void {
            }

            /***/
            public function dropFK(string $table, string $name): void
            {
            }

        };
        $this->assertInstanceOf(ForeignKeyQueries::class, $class);
    }

    /**
     * @return void
     */
    public function testIndexQueriesInterface(): void
    {
        $class = new class implements IndexQueries
        {

            /***/
            public function isIndexExist(string $table, string $index): bool
            {
                return false;
            }

            /***/
            public function addIndex(
                string $table,
                string $name,
                array $columns = []
            ): void {
            }

            /***/
            public function dropIndex(string $table, string $name): void
            {
            }

            /***/
            public function isIndexEqualTo(
                string $table,
                string $index,
                array $columns
            ): bool {
                return false;
            }

        };
        $this->assertInstanceOf(IndexQueries::class, $class);
    }

    /**
     * @return void
     */
    public function testTableQueriesInterface(): void
    {
        $class = new class implements TableQueries
        {

            /***/
            public function isTableExist(string $name): bool
            {
                return false;
            }

            /***/
            public function createTable(
                string $name,
                array $cols,
                array $pk = ['id'],
                string $engine = 'InnoDB'
            ): void {
            }

            /***/
            public function dropTable(string $name): void
            {
            }

        };
        $this->assertInstanceOf(TableQueries::class, $class);
    }

    /**
     * @return void
     */
    public function testDriverContainerTrait(): void
    {
        $class = new class
        {
            use DriverContainer;
        };
        $this->assertTrue(method_exists($class, 'setDriver'));
        $this->assertTrue(method_exists($class, 'getDriver'));
    }

    /**
     * @return void
     */
    public function testConfigurationTrait(): void
    {
        $class = new class
        {
            use Configuration;
        };
        $this->assertTrue(method_exists($class, 'setConfig'));
    }

}
