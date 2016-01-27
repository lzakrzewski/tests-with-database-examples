<?php

namespace Lucaszz\TestsWithDatabaseExamples\Component\Command;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\SchemaTool;
use Lucaszz\TestsWithDatabaseExamples\Component\Config\MysqlConfig;
use Lucaszz\TestsWithDatabaseExamples\Component\Factory\EntityManagerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetupMysqlDatabaseCommand extends Command
{
    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('setup:mysql-database')
            ->setDescription('Prepares mysql database');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setupDatabase();

        $output->writeln(sprintf('Database "%s" created.', MysqlConfig::getParams()['dbname']));
    }

    private function setupDatabase()
    {
        $params        = MysqlConfig::getParams();
        $entityManager = EntityManagerFactory::create($params);
        $name          = $params['dbname'];

        unset($params['dbname']);

        $tmpConnection = DriverManager::getConnection($params);
        $nameEscaped   = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);

        if (in_array($name, $tmpConnection->getSchemaManager()->listDatabases())) {
            $tmpConnection->getSchemaManager()->dropDatabase($nameEscaped);
        }

        $tmpConnection->getSchemaManager()->createDatabase($nameEscaped);
        $schemaTool = new SchemaTool($entityManager);

        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
}
