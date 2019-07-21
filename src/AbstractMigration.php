<?php

namespace Shevaua\DB\Mysql\Migrations;

use Shevaua\DB\Mysql\Migrations\Mysql\DriverContainer;
use Shevaua\DB\Mysql\Migrations\Mysql\SchemaManipulator;

abstract class AbstractMigration implements
    AbleToDowngrade,
    AbleToUpgrade,
    TableQueries,
    IndexQueries,
    ColumnQueries,
    ForeignKeyQueries
{
    use Configuration;
    use DriverContainer;
    use SchemaManipulator;
}
