<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Examples;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use Lucaszz\TestsWithDatabaseExamples\Application\Projection\ListOfItemsProjection;
use Lucaszz\TestsWithDatabaseExamples\Component\Mapping;
use Lucaszz\TestsWithDatabaseExamples\Model\Phone;
use Lucaszz\TestsWithDatabaseExamples\Model\Teapot;
use Lucaszz\TestsWithDatabaseExamples\Tests\TestCase;

class DropAndCreateSchemaTest extends TestCase
{
    /**
     * @test
     * @dataProvider items
     */
    public function items_on_list_could_be_paginated_inefficient()
    {
        $this->dropAndCreateSchema();

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
    public function items_on_list_could_be_paginated_more_efficient()
    {
        $this->purgeDatabase();

        $this->add(new Teapot('brand-new-teapot', 10.0));
        $this->add(new Phone('amazing-phone', 400.0));

        $items = ListOfItemsProjection::create($this->getEntityManager())
            ->get(1, 2);

        $this->assertCount(2, $items);
    }

    public function items()
    {
        return array_fill(1, 10, []);
    }

    private function dropAndCreateSchema()
    {
        $schemaTool = new SchemaTool($this->getEntityManager());

        $metadata = [];

        foreach (Mapping::mappedClasses() as $class) {
            $metadata[] = $this->getEntityManager()->getClassMetadata($class);
        }

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }
}
