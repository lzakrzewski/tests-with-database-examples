<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Examples;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Lucaszz\TestsWithDatabaseExamples\Application\UseCase\ApplyDiscountUseCase;
use Lucaszz\TestsWithDatabaseExamples\Model\Phone;
use Lucaszz\TestsWithDatabaseExamples\Model\Teapot;
use Lucaszz\TestsWithDatabaseExamples\Tests\TestCase;

class CachedMetadataQueriesTest extends TestCase
{
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

    private function restoreClearDatabaseFromMemory()
    {
        $path = SqliteConfig::getParams()['path'];

        if (!self::$backupInMemory) {
            $this->purgeDatabase();
            self::$backupInMemory = file_get_contents($path);
        }

        if (self::$backupInMemory) {
            file_put_contents($path, self::$backupInMemory);
        }
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }
}
