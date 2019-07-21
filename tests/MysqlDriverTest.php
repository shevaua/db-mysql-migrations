<?php

use PHPUnit\Framework\TestCase;
use Shevaua\DB\Mysql\Migrations\Config;
use Shevaua\DB\Mysql\Migrations\Mysql\Driver;
use Shevaua\DB\Mysql\Migrations\Mysql\QueryException;

class MysqlDriverTest extends TestCase
{

    /**
     * @return void
     */
    public function testMysqlDriver(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $driver = new Driver(new Config($params));
        try {
            $driver->run('show tabls');
            $this->assertTrue(false);
        } catch (Throwable $ex) {
            $this->assertInstanceOf(QueryException::class, $ex);
        }

        $result = $driver->run('show tables');
        $this->assertInstanceOf(mysqli_result::class, $result);
    }

}
