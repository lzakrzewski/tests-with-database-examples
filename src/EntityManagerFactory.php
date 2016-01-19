<?php

namespace Lucaszz\TestsWithDatabaseExamples;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Lucaszz\TestsWithDatabaseExamples\Logger\Logger;
use Monolog\Logger as MonologLogger;

final class EntityManagerFactory
{
    public static function create(array $params)
    {
        $entityPath = [__DIR__.'/Entity'];
        $config     = Setup::createAnnotationMetadataConfiguration($entityPath, false);
        $handler    = new \Monolog\Handler\StreamHandler((__DIR__.'/../logs/dev.log'), 100, true, null);
        $logger     = new Logger(new MonologLogger('app', [$handler]));
        $config->setSQLLogger($logger);

        $driver = new AnnotationDriver(new AnnotationReader(), $entityPath);
        AnnotationRegistry::registerLoader('class_exists');
        $config->setMetadataDriverImpl($driver);

        return EntityManager::create($params, $config);
    }
}
