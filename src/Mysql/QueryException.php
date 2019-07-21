<?php

namespace Shevaua\DB\Mysql\Migrations\Mysql;

use Exception;

class QueryException extends Exception
{

    /**
     * @param string $query
     * @param integer $code
     * @param string $message
     */
    public function __construct(string $query, int $code, string $message)
    {
        $lines = explode("\n", $query);
        $message = '#' . $code . "\nError: \n  " . $message ."\nQuery: \n";
        $i = 1;
        foreach ($lines as $line) {
            $message .= $i . ':  ' . $line . "\n";
            $i++;
        }
        parent::__construct(
            $message
        );
    }

}
