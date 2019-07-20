<?php

use PHPUnit\Framework\TestCase;
use Shevaua\DB\Mysql\Migrations\Config;

class ConfigTest extends TestCase
{

    /**
     * @return void
     */
    public function testConfigParams(): void
    {
        $params = [
            'folder' => '/path_to/folder/',
            'db' => 'testing',
            'user' => 'username',
            'passwd' => 'p@sS',
            'host' => 'localhost',
            'port' => '3030',
            'socket' => 'socket',
            'rollback_on_error' => true,
            'table' => 'migrations',
            'ignore' => ['subfolder', 'path_to_migration.php']
        ];
        $config = new Config($params);

        $this->assertEquals(
            $params,
            [
                'folder' => $config->getFolder(),
                'db' => $config->getDB(),
                'user' => $config->getUser(),
                'passwd' => $config->getPasswd(),
                'host' => $config->getHost(),
                'port' => $config->getPort(),
                'socket' => $config->getSocket(),
                'rollback_on_error' => $config->rollbackOnError(),
                'table' => $config->getTable(),
                'ignore' => $config->getIgnoreList(),
            ]
        );
    }

    /**
     * @return void
     */
    public function testDefaultConfig(): void
    {
        $config = new Config([]);

        $this->assertEquals(
            [
                'folder' => '',
                'db' => '',
                'user' => '',
                'passwd' => '',
                'host' => '127.0.0.1',
                'port' => '3306',
                'socket' => '',
                'rollback_on_error' => false,
                'table' => 'migration',
                'ignore' => [],
            ],
            [
                'folder' => $config->getFolder(),
                'db' => $config->getDB(),
                'user' => $config->getUser(),
                'passwd' => $config->getPasswd(),
                'host' => $config->getHost(),
                'port' => $config->getPort(),
                'socket' => $config->getSocket(),
                'rollback_on_error' => $config->rollbackOnError(),
                'table' => $config->getTable(),
                'ignore' => $config->getIgnoreList(),
            ]
        );
    }

}
