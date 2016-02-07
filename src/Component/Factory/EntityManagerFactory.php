<?php

namespace Lzakrzewski\TestsWithDatabaseExamples\Component\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Lzakrzewski\TestsWithDatabaseExamples\Component\Logger\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

final class EntityManagerFactory
{
    private function __construct()
    {
    }

    public static function create(array $params)
    {
        $entityPath = [__DIR__.'/../../Model'];
        $config     = Setup::createAnnotationMetadataConfiguration($entityPath, false);
        $handler    = new StreamHandler((__DIR__.'/../../../var/logs/dev.log'), 100, true, null);
        $logger     = new Logger(new MonologLogger('app', [$handler]));
        $config->setSQLLogger($logger);

        $driver = new AnnotationDriver(new AnnotationReader(), $entityPath);
        AnnotationRegistry::registerLoader('class_exists');
        $config->setMetadataDriverImpl($driver);

        return EntityManager::create($params, $config);
    }
}
