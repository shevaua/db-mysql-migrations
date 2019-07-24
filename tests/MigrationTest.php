<?php

use PHPUnit\Framework\TestCase;
use Shevaua\DB\Mysql\Migrations\Config;
use Shevaua\DB\Mysql\Migrations\MigrationController;
use Shevaua\DB\Mysql\Migrations\Database\DBDriver;
use Shevaua\DB\Mysql\Migrations\Mysql\QueryException;

class MigrationTest extends TestCase
{

    /**
     * @return void
     */
    public function testCase1(): void
    {
        $params = include dirname(__DIR__) . '/config/tests/case1.php';
        $config = new Config($params);
        $controller = new MigrationController($config);
        $dbDriver = new DBDriver($config);

        $this->assertFalse($dbDriver->isTableExist('author'));
        $this->assertFalse($dbDriver->isTableExist('book'));

        $controller->migrate(1);
        $this->assertTrue($dbDriver->isTableExist('author'));

        $controller->migrate(1);
        $this->assertTrue($dbDriver->isTableExist('book'));

        $this->assertFalse($dbDriver->isIndexExist('author', 'name'));
        $this->assertFalse($dbDriver->isIndexExist('book', 'author_id'));

        $controller->migrate(1);
        $this->assertTrue($dbDriver->isIndexExist('author', 'name'));
        $this->assertTrue(
            $dbDriver->isIndexEqualTo('author', 'name', ['name'])
        );

        $controller->migrate(1);
        $this->assertTrue($dbDriver->isIndexExist('book', 'author_id'));
        $this->assertTrue(
            $dbDriver->isIndexEqualTo('book', 'author_id', ['author_id'])
        );
        $this->assertTrue($dbDriver->isIndexExist('book', 'category_title'));
        $this->assertTrue(
            $dbDriver->isIndexEqualTo(
                'book',
                'category_title',
                ['category', 'title']
            )
        );

        $this->assertFalse(
            $dbDriver->isColumnExist('book', 'pages')
        );
        $controller->migrate(1);
        $this->assertTrue(
            $dbDriver->isColumnExist('book', 'pages')
        );

        $this->assertFalse($dbDriver->isFKExist('fk_book_author_id'));
        $controller->migrate(1);
        $this->assertTrue($dbDriver->isFKExist('fk_book_author_id'));

        // clearing

        $controller->rollback();
        $controller->rollback();
        $controller->rollback();
        $controller->rollback();
        $controller->rollback();
        $controller->rollback();
        $dbDriver->dropTable($config->getTable());
    }

    /**
     * Testing transactions
     *
     * @return void
     */
    public function testCase2WithoutTransactions(): void
    {
        $params = include dirname(__DIR__) . '/config/tests/case2.php';
        $config = new Config($params);
        $controller = new MigrationController($config);
        $dbDriver = new DBDriver($config);

        // $this->assertFalse($dbDriver->isTableExist('author2'));

        try {
            $controller->migrate(1);
        } catch (QueryException $e) {
            $this->assertTrue($dbDriver->isTableExist('author2'));
        }

        /* clearing author2 table because rollback won't work */
        $dbDriver->dropTable('author2');
        $dbDriver->dropTable($config->getTable());
    }

    /**
     * Testing transactions #2
     *
     * @return void
     */
    public function testCase2WithTransactions(): void
    {
        $params = include dirname(__DIR__) . '/config/tests/case2.php';
        $params['rollback_on_error'] = true;
        $config = new Config($params);
        $controller = new MigrationController($config);
        $dbDriver = new DBDriver($config);

        $this->assertFalse($dbDriver->isTableExist('author2'));

        try {
            $controller->migrate(1);
            $this->assertFalse(true);
        } catch (QueryException $e) {
            $this->assertFalse($dbDriver->isTableExist('author2'));
        }

        /* clearing */
        $controller->rollback();
        unset($controller);
        $dbDriver->dropTable($config->getTable());
    }

    /**
     * Testing transactions #3
     *
     * @return void
     */
    public function testCase3WithoutTransactions(): void
    {
        $params = include dirname(__DIR__) . '/config/tests/case3.php';
        $config = new Config($params);
        $controller = new MigrationController($config);
        $dbDriver = new DBDriver($config);

        $controller->migrate();
        $this->assertTrue($dbDriver->isTableExist('author3'));
        $this->assertTrue($dbDriver->isIndexExist('author3', 'age'));
        $this->assertTrue($dbDriver->isIndexExist('author3', 'name'));
        try {
            $controller->rollback();
        } catch (QueryException $e) {
            $this->assertTrue($dbDriver->isIndexExist('author3', 'age'));
            $this->assertFalse($dbDriver->isIndexExist('author3', 'name'));
        }

        /* clearing author3 table because rollback won't work */
        $dbDriver->dropTable('author3');
        $dbDriver->dropTable($config->getTable());
    }

    /**
     * Testing transactions #4
     *
     * @return void
     */
    public function testCase3WithTransactions(): void
    {
        $params = include dirname(__DIR__) . '/config/tests/case3.php';
        $params['rollback_on_error'] = true;
        $config = new Config($params);
        $controller = new MigrationController($config);
        $dbDriver = new DBDriver($config);

        $controller->migrate();
        $this->assertTrue($dbDriver->isTableExist('author3'));
        $this->assertTrue($dbDriver->isIndexExist('author3', 'age'));
        $this->assertTrue($dbDriver->isIndexExist('author3', 'name'));
        try {
            $controller->rollback();
        } catch (QueryException $e) {
            $this->assertTrue($dbDriver->isIndexExist('author3', 'age'));
            $this->assertTrue($dbDriver->isIndexExist('author3', 'name'));
        }

        /* clearing author3 table because rollback won't work */
        $dbDriver->dropTable('author3');
        $dbDriver->dropTable($config->getTable());
    }

}
