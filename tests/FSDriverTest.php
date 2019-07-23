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
            'improvements/20190528160400_empty.php',
            'features/somefeature/20190528160500_empty.php',
        ]);

        $migrations = $fsDriver->getMigrations();
        $this->assertEquals($migrations, [
            'improvements/20190528160400_empty.php',
            'features/somefeature/20190528160500_empty.php',
        ]);
    }

}
