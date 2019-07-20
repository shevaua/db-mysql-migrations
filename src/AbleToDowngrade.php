<?php

namespace Shevaua\DB\Mysql\Migrations;

interface AbleToDowngrade
{

    /**
     * Downgrade
     * @return void
     */
    public function down(): void;

}
