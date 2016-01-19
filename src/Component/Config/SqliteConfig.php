<?php

namespace Lucaszz\TestsWithDatabaseExamples\Component\Config;

final class SqliteConfig
{
    /**
     * @return array
     */
    public static function getParams()
    {
        return [
            'driver'   => 'pdo_sqlite',
            'user'     => 'root',
            'password' => '',
            'path'     => __DIR__.'/../../../var/database/sqlite.db',
        ];
    }
}
