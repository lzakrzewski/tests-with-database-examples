<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /** @var bool */
    private static $isDBCreated;

    /** @var EntityManager */
    private $entityManager;

    /** {@inheritdoc} */
    protected function setUp()
    {
        $this->entityManager = $this->createEntityManager();

        if (!self::$isDBCreated) {
            $this->setupDatabase();
            self::$isDBCreated = true;
        }
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

    abstract protected function setupDatabase();

    abstract protected function getParams();

    private function createEntityManager()
    {
        $entityPath = [__DIR__.'/../src/Entity'];
        $config     = Setup::createAnnotationMetadataConfiguration($entityPath, false);
        $driver     = new AnnotationDriver(new AnnotationReader(), $entityPath);
        AnnotationRegistry::registerLoader('class_exists');
        $config->setMetadataDriverImpl($driver);

        return EntityManager::create($this->getParams(), $config);
    }
}
