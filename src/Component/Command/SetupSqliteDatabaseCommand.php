<?php

namespace Lzakrzewski\TestsWithDatabaseExamples\Component\Command;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\SchemaTool;
use Lzakrzewski\TestsWithDatabaseExamples\Component\Config\SqliteConfig;
use Lzakrzewski\TestsWithDatabaseExamples\Component\Factory\EntityManagerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetupSqliteDatabaseCommand extends Command
{
    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('setup:sqlite-database')
            ->setDescription('Prepares sqlite database');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setupDatabase();

        $output->writeln(sprintf('Database "%s" created.', SqliteConfig::getParams()['path']));
    }

    private function setupDatabase()
    {
        $params        = SqliteConfig::getParams();
        $entityManager = EntityManagerFactory::create($params);

        $tmpConnection = DriverManager::getConnection($params);
        $tmpConnection->getSchemaManager()->createDatabase($params['path']);
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();

        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
}
