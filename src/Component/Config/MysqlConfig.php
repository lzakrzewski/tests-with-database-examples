<?php

namespace Lucaszz\TestsWithDatabaseExamples\Component\Config;

final class MysqlConfig
{
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
