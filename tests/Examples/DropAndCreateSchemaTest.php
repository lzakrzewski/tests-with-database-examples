<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Examples;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use Lucaszz\TestsWithDatabaseExamples\Application\Projection\ListOfItemsProjection;
use Lucaszz\TestsWithDatabaseExamples\Application\UseCase\ApplyDiscountUseCase;
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
    public function discount_could_be_applied_on_items_in_inefficient_way()
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
    public function discount_could_be_applied_on_items_in_more_efficient_way()
    {
        $this->purgeDatabase();

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
