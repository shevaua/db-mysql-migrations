# How To?

1) You need an array with configuration params. Ex: /config/sample.php
   
        $params = [
            'folder' => 'path_to_folder',
            'ignore' => [
                // list of ignored subpathes
            ],
            ...
        ];

2) Create a config instance like this one

        $config = new Shevaua\DB\Mysql\Migrations\Config($params);

3) Create a controller instance

        $controller = new Shevaua\DB\Mysql\Migrations\MigrationController($config);

4) Let's start

        /** Execute all new migrations */
        $controller->migrate();

        /** Rollback for one step back */
        $controller->rollback();

# Migration table

Migration table contains next columns:
+ id: _int_
+ group: _int_
+ name: _varchar_
+ migrated_at: _timestamp_

# Advanced

There is a way to migrate/rollback migrations step by step

    /** Execute all new migrations */
    $controller->migrate($limit = 1);

    /** Rollback for one step back */
    $controller->rollback($limit = 1);

# CHANGELOG

[click here](/CHANGELOG.md)

# CONTRIBUTE

[click here](/CONTRIBUTE.md)
