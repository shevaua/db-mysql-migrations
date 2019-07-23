<?php

use Shevaua\DB\Mysql\Migrations\AbstractMigration;

return new class extends AbstractMigration
{

    /**
     * @return void
     */
    public function up(): void
    {
        $table = $this->config->getTable();

        if (!$this->isTableExist($table)) {
            $this->createTable($table, [
                'id' => 'int(10) unsigned not null auto_increment',
                'group' => 'int(10) unsigned not null',
                'name' => 'varchar(255) not null',
                'migrated_at' => 'timestamp not null',
            ]);
        }

        if (!$this->isIndexExist($table, 'name')) {
            $this->addIndex($table, 'name');
        }

        if (!$this->isIndexExist($table, 'group')) {
            $this->addIndex($table, 'group');
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function down(): void
    {
        throw new Exception('There is no reason to remove migration table');
    }

};
