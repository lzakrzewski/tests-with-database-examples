<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Examples;

use Doctrine\DBAL\Schema\Table;
use Lucaszz\TestsWithDatabaseExamples\Application\UseCase\ApplyDiscountUseCase;
use Lucaszz\TestsWithDatabaseExamples\Model\Phone;
use Lucaszz\TestsWithDatabaseExamples\Model\Teapot;
use Lucaszz\TestsWithDatabaseExamples\Tests\TestCase;

class CachedMetadataQueriesTest extends TestCase
{
    /** @var string */
    private static $purgeQueryInMemory;

    /**
     * @test
     * @dataProvider items
     */
    public function discount_could_be_applied_on_items_in_more_efficient_way()
    {
        $this->executedCachedQueryToPurgeDatabase();

        $this->add(new Teapot('brand-new-teapot', 100.0));
        $this->add(new Phone('amazing-phone', 400.0));

        ApplyDiscountUseCase::create($this->getEntityManager())
            ->apply(0.5);

        $this->assertEquals(50, $this->findItemByName('brand-new-teapot')->price());
        $this->assertEquals(200, $this->findItemByName('amazing-phone')->price());
    }

    private function executedCachedQueryToPurgeDatabase()
    {
        $conn = $this->getEntityManager()->getConnection();

        if (null !== self::$purgeQueryInMemory) {
            $conn->exec(self::$purgeQueryInMemory);

            return;
        }

        $tables = $this->orderedTables($conn->getSchemaManager()->listTables());

        $tableNames = array_map(function (Table $table) {
            return $table->getName();
        }, $tables);

        self::$purgeQueryInMemory = '';

        foreach ($tableNames as $tableName) {
            self::$purgeQueryInMemory .= sprintf('DELETE FROM %s;', $tableName);
        }

        $conn->exec(self::$purgeQueryInMemory);
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
