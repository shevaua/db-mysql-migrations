<?php

use PHPUnit\Framework\TestCase;
use Shevaua\DB\Mysql\Migrations\Config;
use Shevaua\DB\Mysql\Migrations\MigrationController;
use Shevaua\DB\Mysql\Migrations\Database\DBDriver;

class ControllerTest extends TestCase
{

    /**
     * @return void
     */
    public function testMigration(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        $controller = new MigrationController($config);

        $dbDriver = new DBDriver($config);

        $this->assertEquals(
            array_values($dbDriver->getMigrations()),
            []
        );
        $this->assertEquals(0, $dbDriver->getLastGroup());

        $controller->migrate();

        $this->assertEquals(
            array_values($dbDriver->getMigrations()),
            [
                'improvements/20190528160400_empty.php',
                'features/somefeature/20190528160500_empty.php',
            ]
        );
        $this->assertEquals(1, $dbDriver->getLastGroup());
    }

    /**
     * @return void
     */
    public function testReset(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        $controller = new MigrationController($config);

        $dbDriver = new DBDriver($config);
        $this->assertEquals(1, $dbDriver->getLastGroup());
        $this->assertEquals(
            array_values($dbDriver->getMigrations()),
            [
                'improvements/20190528160400_empty.php',
                'features/somefeature/20190528160500_empty.php',
            ]
        );

        $controller->reset();

        $this->assertEquals(
            array_values($dbDriver->getMigrations()),
            [
                'improvements/20190528160400_empty.php',
                'features/somefeature/20190528160500_empty.php',
            ]
        );
        $this->assertEquals(1, $dbDriver->getLastGroup());
    }

    /**
     * @return void
     */
    public function testRollback(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        $controller = new MigrationController($config);
        $controller->rollback();

        $dbDriver = new DBDriver($config);
        $this->assertEquals(
            $dbDriver->getMigrations(),
            []
        );
        $this->assertEquals(0, $dbDriver->getLastGroup());
    }

    /**
     * @return void
     */
    public function testMigrationWithSteps(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        $controller = new MigrationController($config);

        $dbDriver = new DBDriver($config);
        $this->assertEquals(
            $dbDriver->getMigrations(),
            []
        );
        $this->assertEquals(0, $dbDriver->getLastGroup());

        $controller->migrate(1);

        $this->assertEquals(
            array_values($dbDriver->getMigrations()),
            [
                'improvements/20190528160400_empty.php'
            ]
        );
        $this->assertEquals(1, $dbDriver->getLastGroup());

        $controller->migrate();

        $this->assertEquals(
            array_values($dbDriver->getMigrations()),
            [
                'improvements/20190528160400_empty.php',
                'features/somefeature/20190528160500_empty.php',
            ]
        );
        $this->assertEquals(2, $dbDriver->getLastGroup());
    }

    /**
     * @return void
     */
    public function testRollbackWithSteps(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        $controller = new MigrationController($config);

        $dbDriver = new DBDriver($config);
        $this->assertEquals(
            array_values($dbDriver->getMigrations()),
            [
                'improvements/20190528160400_empty.php',
                'features/somefeature/20190528160500_empty.php',
            ]
        );
        $this->assertEquals(2, $dbDriver->getLastGroup());

        $controller->rollback();

        $this->assertEquals(
            array_values($dbDriver->getMigrations()),
            [
                'improvements/20190528160400_empty.php',
            ]
        );
        $this->assertEquals(1, $dbDriver->getLastGroup());

        $controller->rollback();

        $this->assertEquals(
            $dbDriver->getMigrations(),
            []
        );
        $this->assertEquals(0, $dbDriver->getLastGroup());
        $dbDriver->dropTable('migration');
    }

}
