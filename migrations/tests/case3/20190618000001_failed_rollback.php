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
        if (!$this->isIndexExist($this->table, 'age')) {
            $this->addIndex($this->table, 'age');
        }

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

        if ($this->isIndexExist($this->table, 'age')) {
            // failure
            $this->dropIndex($this->table, 'name');
        }
    }

};
