<?php

namespace Shevaua\DB\Mysql\Migrations;

use Shevaua\DB\Mysql\Migrations\Database\DBDriver;

class MigrationController
{
    use Configuration;

    /** @var DBDriver */
    protected $dbDriver;

    /** @var int */
    protected $group = 1;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);
        $this->dbDriver = new DBDriver($config);
        $this->init();
    }

    /**
     * Initializion for controller
     *      1. Run init migration to make sure table exists
     *      2. Check for current step
     *
     * @return void
     */
    protected function init(): void
    {
        if ($this->config->rollbackOnError()) {
            $this->dbDriver->setAutocommit(false);
            $this->dbDriver->startTransaction();
        }

        /** @var AbstractMigration First Migration ever */
        $firstMigration = include dirname(__DIR__)
            . '/migrations/20190527000000_initial.php';

        $firstMigration
            ->setDriver($this->dbDriver->getDriver())
            ->setConfig($this->config)
            ->up();

        if ($this->config->rollbackOnError()) {
            $this->dbDriver->stopTransaction(true);
        }

        $this->group = $this->dbDriver->getLastGroup() + 1;
    }

}
