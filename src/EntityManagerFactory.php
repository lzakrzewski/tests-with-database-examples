<?php

namespace Lucaszz\TestsWithDatabaseExamples;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

final class EntityManagerFactory
{
    public static function create(array $params)
    {
        $entityPath = [__DIR__.'/Entity'];
        $config     = Setup::createAnnotationMetadataConfiguration($entityPath, false);

        $driver = new AnnotationDriver(new AnnotationReader(), $entityPath);
        AnnotationRegistry::registerLoader('class_exists');
        $config->setMetadataDriverImpl($driver);

        return EntityManager::create($params, $config);
    }
}
