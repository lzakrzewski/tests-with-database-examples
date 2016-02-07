<?php

namespace Lzakrzewski\TestsWithDatabaseExamples\Tests\Examples;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Schema\Table;
use Doctrine\ORM\Tools\SchemaTool;
use Lzakrzewski\TestsWithDatabaseExamples\Application\UseCase\ApplyDiscountUseCase;
use Lzakrzewski\TestsWithDatabaseExamples\Model\Phone;
use Lzakrzewski\TestsWithDatabaseExamples\Model\Teapot;
use Lzakrzewski\TestsWithDatabaseExamples\Tests\TestCase;

class EmptyDatabaseTest extends TestCase
{
    /** @var string */
    private static $purgeQueryInMemory;

    /**
     * @test
     * @dataProvider items
     */
    public function drop_and_create_database()
    {
        $this->dropAndCreateSchema();

        $this->add(new Teapot('brand-new-teapot', 100.0));
        $this->add(new Phone('amazing-phone', 400.0));

        ApplyDiscountUseCase::create($this->getEntityManager())
            ->apply(0.5);

        $this->assertEquals(50, $this->findItemByName('brand-new-teapot')->price());
        $this->assertEquals(200, $this->findItemByName('amazing-phone')->price());
    }

    /**
     * @test
     * @dataProvider items
     */
    public function purge_database()
    {
        $this->purgeDatabase();

        $this->add(new Teapot('brand-new-teapot', 100.0));
        $this->add(new Phone('amazing-phone', 400.0));

        ApplyDiscountUseCase::create($this->getEntityManager())
            ->apply(0.5);

        $this->assertEquals(50, $this->findItemByName('brand-new-teapot')->price());
        $this->assertEquals(200, $this->findItemByName('amazing-phone')->price());
    }

    /**
     * @test
     * @dataProvider items
     */
    public function purge_database_with_cached_purge_query()
    {
        $this->executedCachedQueryToPurgeDatabase();

        $this->add(new Teapot('brand-new-teapot', 100.0));
        $this->add(new Phone('amazing-phone', 400.0));

        ApplyDiscountUseCase::create($this->getEntityManager())
            ->apply(0.5);

        $this->assertEquals(50, $this->findItemByName('brand-new-teapot')->price());
        $this->assertEquals(200, $this->findItemByName('amazing-phone')->price());
    }

    private function dropAndCreateSchema()
    {
        $schemaTool = new SchemaTool($this->getEntityManager());
        $metadata   = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    private function executedCachedQueryToPurgeDatabase()
    {
        $conn = $this->getEntityManager()->getConnection();

        if (null === self::$purgeQueryInMemory) {
            self::$purgeQueryInMemory = $this->createQueryToPurgeDatabase();
        }

        $conn->exec(self::$purgeQueryInMemory);
    }

    private function createQueryToPurgeDatabase()
    {
        $conn = $this->getEntityManager()->getConnection();

        $tables = $this->orderedTables($conn->getSchemaManager()->listTables());

        $tableNames = array_map(function (Table $table) {
            return $table->getName();
        }, $tables);

        $query = '';

        foreach ($tableNames as $tableName) {
            $query .= sprintf('DELETE FROM %s;', $tableName);
        }

        return $query;
    }

    private function orderedTables(array $unorderedTables)
    {
        $orderedTables = [];

        foreach ($unorderedTables as $table) {
            $foreignKeys = $table->getForeignKeys();
            if (!empty($foreignKeys)) {
                $orderedTables[] = $table;
            }
        }

        foreach ($unorderedTables as $table) {
            $foreignKeys = $table->getForeignKeys();
            if (empty($foreignKeys)) {
                $orderedTables[] = $table;
            }
        }

        return $orderedTables;
    }
}
