<?php

use Shevaua\DB\Mysql\Migrations\AbstractMigration;

return new class extends AbstractMigration
{

    /** @var string */
    private $table = 'author3';

    /**
     * @return void
     */
    public function up(): void
    {
        if (!$this->isTableExist($this->table)) {
            $this->createTable($this->table, [
                'id' => 'int(10) unsigned not null auto_increment',
                'age' => 'int(10) unsigned not null',
                'name' => 'varchar(255) not null',
            ]);
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if ($this->isTableExist($this->table)) {
            $this->dropTable($this->table);
        }
    }

};
