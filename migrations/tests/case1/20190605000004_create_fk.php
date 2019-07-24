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
        if (!$this->isFKExist('fk_book_author_id')) {
            $this->addFK(
                $this->table,
                'fk_book_author_id',
                ['author_id'],
                'author',
                ['id']
            );
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if ($this->isFKExist('fk_book_author_id')) {
            $this->dropFK($this->table, 'fk_book_author_id');
        }
    }

};
