<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Examples;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Lucaszz\TestsWithDatabaseExamples\ListOfItems;
use Lucaszz\TestsWithDatabaseExamples\Model\Phone;
use Lucaszz\TestsWithDatabaseExamples\Model\Teapot;
use Lucaszz\TestsWithDatabaseExamples\Tests\TestCase;

class NativeQueriesTest extends TestCase
{
    /**
     * @test
     * @dataProvider items
     */
    public function items_on_list_could_be_paginated_inefficient()
    {
        $this->purgeDatabaseWithPurger();

        $this->add(new Teapot('brand-new-teapot', 10.0));
        $this->add(new Phone('amazing-phone', 400.0));

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
        $this->purgeDatabaseWithNativeQueries();

        $this->add(new Teapot('brand-new-teapot', 10.0));
        $this->add(new Phone('amazing-phone', 400.0));

        $items = ListOfItems::create($this->getEntityManager())
            ->get(1, 2);

        $this->assertCount(2, $items);
    }

    public function items()
    {
        return array_fill(1, 100, []);
    }

    private function purgeDatabaseWithPurger()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    private function purgeDatabaseWithNativeQueries()
    {
        $sql = <<<SQL
DELETE FROM Item;
DELETE FROM juices;
DELETE FROM apples;
DELETE FROM waters;
DELETE FROM teapots;
DELETE FROM mangos;
DELETE FROM beers;
DELETE FROM phones;
DELETE FROM hair_dryers;
DELETE FROM blenders;
DELETE FROM glasses;
SQL;

        $this->getEntityManager()->getConnection()->exec($sql);
    }
}
