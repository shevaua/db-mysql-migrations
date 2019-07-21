<?php

use PHPUnit\Framework\TestCase;
use Shevaua\DB\Mysql\Migrations\Config;
use Shevaua\DB\Mysql\Migrations\FileSystem\FSDriver;

class FSDriverTest extends TestCase
{

    /**
     * @return void
     */
    public function testFSDriver(): void
    {
        $params = include dirname(__DIR__) . '/config/sample.php';
        $config = new Config($params);
        $fsDriver = new FSDriver($config);

        $migrations = $fsDriver->getMigrations();
        $this->assertEquals($migrations, [
            '.gitignore',
        ]);

        $migrations = $fsDriver->getMigrations();
        $this->assertEquals($migrations, [
            '.gitignore',
        ]);
    }

}
