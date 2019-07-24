<?php

use Shevaua\DB\Mysql\Migrations\AbstractMigration;

return new class extends AbstractMigration
{
    /** @var string */
    private $table = 'book';

    /**
     * @return void
     */
    public function up(): void
    {
        if (!$this->isTableExist($this->table)) {
            $this->createTable($this->table, [
                'id' => 'int(10) unsigned not null auto_increment',
                'author_id' => 'int(10) unsigned not null',
                'category' => 'varchar(255) not null',
                'title' => 'varchar(255) not null',
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
