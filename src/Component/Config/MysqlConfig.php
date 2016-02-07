<?php

namespace Lzakrzewski\TestsWithDatabaseExamples\Component\Config;

final class MysqlConfig
{
    private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function getParams()
    {
        return [
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => '',
            'dbname'   => 'database-test',
        ];
    }
}
