<?php

namespace Lucaszz\TestsWithDatabaseExamples\Dictionary;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\SchemaTool;
use Lucaszz\TestsWithDatabaseExamples\Entity\Item;

trait MySqlDictionary
{
    /**
     * @return array
     */
    protected function getParams()
    {
        return [
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => '',
            'dbname'   => 'database-test',
        ];
    }

    protected function setupDatabase()
    {
        $params = $this->getParams();
        $name   = $params['dbname'];

        unset($params['dbname']);

        $tmpConnection = DriverManager::getConnection($params);
        $nameEscaped   = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);

        if (in_array($name, $tmpConnection->getSchemaManager()->listDatabases())) {
            $tmpConnection->getSchemaManager()->dropDatabase($nameEscaped);
        }

        $tmpConnection->getSchemaManager()->createDatabase($nameEscaped);
        $schemaTool = new SchemaTool($this->getEntityManager());

        $metadata = [];

        foreach (Item::getClasses() as $class) {
            $metadata[] = $this->getEntityManager()->getClassMetadata($class);
        }

        $schemaTool->createSchema($metadata);
    }
}
