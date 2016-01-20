<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Examples;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Lucaszz\TestsWithDatabaseExamples\Application\Projection\ListOfItemsProjection;
use Lucaszz\TestsWithDatabaseExamples\Component\Config\SqliteConfig;
use Lucaszz\TestsWithDatabaseExamples\Component\Fixtures\LoadItems;
use Lucaszz\TestsWithDatabaseExamples\Model\Phone;
use Lucaszz\TestsWithDatabaseExamples\Model\Teapot;
use Lucaszz\TestsWithDatabaseExamples\Tests\TestCase;

class CachedSqliteDatabaseTest extends TestCase
{
    /** @var bool */
    private static $backupInMemory = null;

    /**
     * @test
     * @dataProvider items
     */
    public function items_could_be_updated_with_mysql()
    {
        $this->givenMysqlDatabaseWasConnected();
        $this->loadWholeFixtures();

        $this->add(new Teapot('brand-new-teapot', 10.0));
        $this->add(new Phone('amazing-phone', 400.0));

        $items = ListOfItemsProjection::create($this->getEntityManager())
            ->get(1, 2);

        $this->assertCount(2, $items);
    }

    /**
     * @test
     * @dataProvider items
     */
    public function items_could_be_updated_with_sqlite()
    {
        $this->givenSqliteDatabaseWasConnected();

        $params = SqliteConfig::getParams();

        if (!self::$backupInMemory) {
            $this->loadWholeFixtures();
            self::$backupInMemory = file_get_contents($params['path']);
        }

        if (self::$backupInMemory) {
            file_put_contents($params['path'], self::$backupInMemory);
        }

        $this->add(new Teapot('brand-new-teapot', 10.0));
        $this->add(new Phone('amazing-phone', 400.0));

        $items = ListOfItemsProjection::create($this->getEntityManager())
            ->get(1, 2);

        $this->assertCount(2, $items);
    }

    private function loadWholeFixtures()
    {
        $loader = new Loader();
        $loader->addFixture(new LoadItems());

        $purger   = new ORMPurger();
        $executor = new ORMExecutor($this->getEntityManager(), $purger);
        $executor->execute($loader->getFixtures());
    }

    public function items()
    {
        return array_fill(1, 500, []);
    }
}
