<?php

use PHPUnit\Framework\TestCase;
use Shevaua\DB\Mysql\Migrations\Config;
use Shevaua\DB\Mysql\Migrations\MigrationController;
use Shevaua\DB\Mysql\Migrations\Database\DBDriver;

class DBDriverTest extends TestCase
{

    /**
     * @return void
     */
    public function testInitialization(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        new MigrationController($config);

        $dbDriver = new DBDriver($config);
        $this->assertTrue($dbDriver->isTableExist($config->getTable()));
        $this->assertEquals(0, $dbDriver->getLastGroup());
    }

    /**
     * @return void
     */
    public function testGetMigrations(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        $dbDriver = new DBDriver($config);
        $this->assertEquals(
            [],
            $dbDriver->getMigrations()
        );
        $this->assertEquals(0, $dbDriver->getLastGroup());
    }

    /**
     * @return void
     */
    public function testRegistering(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        $dbDriver = new DBDriver($config);

        $this->assertEquals(0, $dbDriver->getLastGroup());

        $dbDriver->register('phpunit1', 1);
        $dbDriver->register('phpunit2', 1);
        $dbDriver->register('phpunit3', 2);
        $dbDriver->register('phpunit4', 2);

        $this->assertEquals(2, $dbDriver->getLastGroup());
        $this->assertEquals(
            [
                'phpunit1',
                'phpunit2',
                'phpunit3',
                'phpunit4',
            ],
            array_values($dbDriver->getMigrations())
        );

        $this->assertEquals(
            [
                'phpunit4',
                'phpunit3',
            ],
            array_values($dbDriver->getMigrations('desc', 2))
        );
    }

    /**
     * @return void
     */
    public function testUnregistering(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        $dbDriver = new DBDriver($config);

        $this->assertEquals(2, $dbDriver->getLastGroup());
        $dbDriver->unregister('phpunit3');

        $this->assertEquals(2, $dbDriver->getLastGroup());

        $this->assertEquals(
            [
                'phpunit4',
            ],
            array_values($dbDriver->getMigrations('desc', 2))
        );

        $dbDriver->unregister('phpunit4');
        $this->assertEquals(1, $dbDriver->getLastGroup());

        $this->assertEquals(
            [
                'phpunit1',
                'phpunit2',
            ],
            array_values($dbDriver->getMigrations())
        );

        $dbDriver->unregister('phpunit1');
        $dbDriver->unregister('phpunit2');

        $this->assertEquals(
            [],
            $dbDriver->getMigrations()
        );

        $dbDriver->dropTable($config->getTable());
    }

}
