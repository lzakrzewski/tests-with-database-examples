<?php

namespace Lucaszz\TestsWithDatabaseExamples\Command;

use Doctrine\ORM\EntityManager;
use Lucaszz\TestsWithDatabaseExamples\Dictionary\MySqlDictionary;
use Lucaszz\TestsWithDatabaseExamples\EntityManagerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetupDatabaseCommand extends Command
{
    use MySqlDictionary;

    /** @var EntityManager */
    private $entityManager;

    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('setup:database')
            ->setDescription('Prepares database');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = EntityManagerFactory::create($this->getParams());

        $this->setupDatabase();

        $output->writeln(sprintf('Database "%s" created.', $this->getParams()['dbname']));
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }
}
