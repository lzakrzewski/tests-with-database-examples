<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Lucaszz\TestsWithDatabaseExamples\EntityManagerFactory;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /** @var EntityManager */
    private $entityManager;

    /** {@inheritdoc} */
    protected function setUp()
    {
        $this->entityManager = EntityManagerFactory::create($this->getParams());
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

    abstract protected function setupDatabase();

    abstract protected function getParams();
}
