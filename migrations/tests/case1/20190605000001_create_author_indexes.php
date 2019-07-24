<?php

use Shevaua\DB\Mysql\Migrations\AbstractMigration;

return new class extends AbstractMigration
{
    /** @var string */
    private $table = 'author';

    /**
     * @return void
     */
    public function up(): void
    {
        if (!$this->isIndexExist($this->table, 'name')) {
            $this->addIndex($this->table, 'name');
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if ($this->isIndexExist($this->table, 'name')) {
            $this->dropIndex($this->table, 'name');
        }
    }

};
