<?php

namespace Shevaua\DB\Mysql\Migrations;

interface AbleToUpgrade
{

    /**
     * Upgrade
     * @return void
     */
    public function up(): void;

}
