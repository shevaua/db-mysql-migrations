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
        if (!$this->isIndexExist($this->table, 'author_id')) {
            $this->addIndex($this->table, 'author_id');
        }

        if (!$this->isIndexExist($this->table, 'category_title')) {
            $this->addIndex(
                $this->table,
                'category_title',
                [
                    'category',
                    'title',
                ]
            );
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if ($this->isIndexExist($this->table, 'author_id')) {
            $this->dropIndex($this->table, 'author_id');
        }

        if ($this->isIndexExist($this->table, 'category_title')) {
            $this->dropIndex($this->table, 'category_title');
        }
    }

};
