<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Lucaszz\TestsWithDatabaseExamples\Component\Dictionary\MySqlDictionary;
use Lucaszz\TestsWithDatabaseExamples\Component\Fixtures\LoadItems;
use Lucaszz\TestsWithDatabaseExamples\ListOfItems;
use Lucaszz\TestsWithDatabaseExamples\Model\Phone;
use Lucaszz\TestsWithDatabaseExamples\Model\Teapot;

class FixturesLoadingTest extends TestCase
{
    use MySqlDictionary;

    /**
     * @test
     * @dataProvider items
     */
    public function items_on_list_could_be_paginated_inefficient()
    {
        $this->loadWholeFixtures();

        $items = ListOfItems::create($this->getEntityManager())
            ->get(1, 2);

        $this->assertCount(2, $items);
    }

    /**
     * @test
     * @dataProvider items
     */
    public function items_on_list_could_be_paginated_more_efficient()
    {
        $this->givenDatabaseIsClear();

        $this->add(new Teapot('brand-new-teapot', 10.0));
        $this->add(new Phone('amazing-phone', 400.0));

        $items = ListOfItems::create($this->getEntityManager())
            ->get(1, 2);

        $this->assertCount(2, $items);
    }

    public function items()
    {
        return array_fill(1, 500, []);
    }

    private function loadWholeFixtures()
    {
        $loader = new Loader();
        $loader->addFixture(new LoadItems());

        $purger   = new ORMPurger();
        $executor = new ORMExecutor($this->getEntityManager(), $purger);
        $executor->execute($loader->getFixtures());
    }
}
