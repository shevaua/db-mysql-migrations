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
        if (!$this->isColumnExist($this->table, 'pages')) {
            $this->addColumn(
                $this->table,
                'pages',
                'int(10) unsigned',
                'author_id'
            );
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if ($this->isColumnExist($this->table, 'pages')) {
            $this->dropColumn($this->table, 'pages');
        }
    }

};
