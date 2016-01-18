<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Lucaszz\TestsWithDatabaseExamples\Entity\Phone;
use Lucaszz\TestsWithDatabaseExamples\Entity\Teapot;
use Lucaszz\TestsWithDatabaseExamples\ListOfItems;
use Lucaszz\TestsWithDatabaseExamples\Tests\Dictionary\MySqlDictionary;

class PurgeDatabaseTest extends TestCase
{
    use MySqlDictionary;

    /**
     * @test
     * @dataProvider items
     */
    public function items_on_list_could_be_paginated_inefficient()
    {
        $this->purgeDatabase();

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
        $this->purgeDatabaseInSingleTransaction();

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

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    private function purgeDatabaseInSingleTransaction()
    {
        $entityManager = $this->getEntityManager();

        $entityManager->beginTransaction();

        $purger = new ORMPurger($entityManager);
        $purger->purge();

        $entityManager->commit();
    }
}
