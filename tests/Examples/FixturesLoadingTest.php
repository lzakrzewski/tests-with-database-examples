<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Examples;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Lucaszz\TestsWithDatabaseExamples\Application\UseCase\ApplyDiscountUseCase;
use Lucaszz\TestsWithDatabaseExamples\Component\Fixtures\LoadItems;
use Lucaszz\TestsWithDatabaseExamples\Model\Phone;
use Lucaszz\TestsWithDatabaseExamples\Model\Teapot;
use Lucaszz\TestsWithDatabaseExamples\Tests\TestCase;

class FixturesLoadingTest extends TestCase
{
    /**
     * @test
     * @dataProvider items
     */
    public function discount_could_be_applied_on_items_in_inefficient_way()
    {
        $this->loadWholeFixtures();

        ApplyDiscountUseCase::create($this->getEntityManager())
            ->apply(0.5);

        $this->assertEquals(50, $this->findItemByName('teapot_1')->price());
        $this->assertEquals(200, $this->findItemByName('phone_1')->price());
    }

    /**
     * @test
     * @dataProvider items
     */
    public function discount_could_be_applied_on_items_in_more_efficient_way()
    {
        $this->givenDatabaseIsClear();

        $this->add(new Teapot('brand-new-teapot', 100.0));
        $this->add(new Phone('amazing-phone', 400.0));

        ApplyDiscountUseCase::create($this->getEntityManager())
            ->apply(0.5);

        $this->assertEquals(50, $this->findItemByName('brand-new-teapot')->price());
        $this->assertEquals(200, $this->findItemByName('amazing-phone')->price());
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
