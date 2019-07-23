<?php

namespace Shevaua\DB\Mysql\Migrations;

use Exception;
use Shevaua\DB\Mysql\Migrations\Database\DBDriver;
use Shevaua\DB\Mysql\Migrations\FileSystem\FSDriver;
use Shevaua\DB\Mysql\Migrations\Mysql\QueryException;

class MigrationController
{
    use Configuration;

    /** @var DBDriver */
    protected $dbDriver;

    /** @var FSDriver */
    protected $fsDriver;

    /** @var int */
    protected $group = 1;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);
        $this->dbDriver = new DBDriver($config);
        $this->fsDriver = new FSDriver($config);
        $this->init();
    }

    /**
     * Initializion for controller
     *      1. Check for current group
     *      2. Run init migration to make sure table exists
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

    /**
     * @throws Exception
     */
    protected function upMigration(
        string $filename,
        bool $downOnFailure = false
    ): bool {
        $path = $this->config->getFolder() . '/' . $filename;

        $migration = include $path;

        if (!$migration instanceof AbstractMigration) {
            throw new Exception('Migration file is incorrect: '.$path);
        }

        try {
            if ($this->config->rollbackOnError()) {
                $this->dbDriver->startTransaction();
            }

            $migration
                ->setDriver($this->dbDriver->getDriver())
                ->setConfig($this->config)
                ->up();

            if ($downOnFailure) {
                $this->dbDriver
                    ->register($filename, $this->group);
            }

            if ($this->config->rollbackOnError()) {
                $this->dbDriver->stopTransaction(true);
            }
        } catch (QueryException $e) {
            if ($this->config->rollbackOnError()) {
                $this->dbDriver->stopTransaction(false);
                if ($downOnFailure) {
                    $this->downMigration($filename);
                }
            }
            throw $e;
        }

        return true;
    }

    /**
     * @throws Exception
     */
    protected function downMigration(
        string $filename,
        bool $upOnFailure = false
    ): bool {
        $path = $this->config->getFolder() . '/' . $filename;

        $migration = include $path;

        if (!$migration instanceof AbstractMigration) {
            throw new Exception('Migration file is incorrect: '.$path);
        }

        try {
            if ($this->config->rollbackOnError()) {
                $this->dbDriver->startTransaction();
            }

            $migration
                ->setDriver($this->dbDriver->getDriver())
                ->setConfig($this->config)
                ->down();

            if ($upOnFailure) {
                $this->dbDriver
                    ->unregister($filename);
            }

            if ($this->config->rollbackOnError()) {
                $this->dbDriver->stopTransaction(true);
            }
        } catch (QueryException $e) {
            if ($this->config->rollbackOnError()) {
                $this->dbDriver->stopTransaction(false);
                if ($upOnFailure) {
                    $this->upMigration($filename);
                }
            }
            throw $e;
        }

        return true;
    }

    /**
     * Run migrations
     * @param int $limit
     * @return void
     * @throws Exception
     */
    public function migrate(int $limit = 1000): void
    {
        /** @var array List of all found migrations */
        $all = $this->fsDriver->getMigrations();

        /** @var array List of all migrated files in ascending order */
        $migrated = $this->dbDriver->getMigrations('asc');

        foreach ($all as $filename) {
            if (in_array($filename, $migrated)) {
                continue;
            }

            if ($limit-- <= 0) {
                break;
            }

            $this->upMigration($filename, $downOnFailure = true);
        }

        $this->group++;
    }

    /**
     * Rollback
     * @param int $limit
     * @return void
     * @throws Exception
     */
    public function rollback(int $limit = 1000): void
    {
        /**
         * @var array List of migrated files of last group
         *            in descending order
         */
        $migrated = $this->dbDriver
            ->getMigrations('desc', $this->group - 1);

        /** @var array List of all found migrations */
        $all = $this->fsDriver->getMigrations();

        foreach ($migrated as $filename) {
            if (!in_array($filename, $all)) {
                throw new Exception(
                    'Migration '.$filename.' is not found!'
                    . ' Might be incorrect settings or wrong branch'
                );
            }

            if ($limit-- <= 0) {
                break;
            }

            $this->downMigration($filename, $upOnFailure = true);
        }

        $this->group--;
    }

    /**
     * Reset all migrations
     * @throws Exception
     */
    public function reset(): void
    {
        /** @var array List of all found migrations */
        $all = $this->fsDriver->getMigrations();

        /** @var array List of all migrated files in descending order */
        $migrated = $this->dbDriver->getMigrations('desc');

        foreach ($migrated as $filename) {
            if (!in_array($filename, $all)) {
                throw new Exception(
                    'Migration '.$filename.' is not found!'
                    . ' Might be incorrect settings or wrong branch'
                );
            }

            $this->downMigration($filename);
        }

        $this->group = 1;

        $this->migrate();
    }

}
