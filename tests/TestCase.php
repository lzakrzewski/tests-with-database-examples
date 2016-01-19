<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Lucaszz\TestsWithDatabaseExamples\Component\Config\MysqlConfig;
use Lucaszz\TestsWithDatabaseExamples\Component\Config\SqliteConfig;
use Lucaszz\TestsWithDatabaseExamples\Component\Factory\EntityManagerFactory;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /** @var EntityManager */
    private $entityManager;

    /** {@inheritdoc} */
    protected function setUp()
    {
        $this->givenMysqlDatabaseWasConnected();
    }

    protected function givenMysqlDatabaseWasConnected()
    {
        $this->entityManager = EntityManagerFactory::create(MysqlConfig::getParams());
    }

    protected function givenSqliteDatabaseWasConnected()
    {
        $this->entityManager = EntityManagerFactory::create(SqliteConfig::getParams());
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->entityManager = null;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }
    /** Todo: It should be flushed only once */
    protected function add($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    protected function givenDatabaseIsClear()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }
}
