<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Lucaszz\TestsWithDatabaseExamples\Entity\Teapot;
use Lucaszz\TestsWithDatabaseExamples\Fixtures\LoadItems;
use Lucaszz\TestsWithDatabaseExamples\Tests\Dictionary\MySqlDictionary;

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

        $this->assertThatThereIsTeapot();
    }

    /**
     * @test
     * @dataProvider items
     */
    public function items_on_list_could_be_paginated_more_efficient()
    {
        $this->add(new Teapot('teapot', 1.0));

        $this->assertThatThereIsTeapot();
    }

    public function items()
    {
        return array_fill(1, 100, []);
    }

    private function loadWholeFixtures()
    {
        $loader = new Loader();
        $loader->addFixture(new LoadItems());

        $purger   = new ORMPurger();
        $executor = new ORMExecutor($this->getEntityManager(), $purger);
        $executor->execute($loader->getFixtures());
    }

    private function assertThatThereIsTeapot()
    {
        $this->assertNotEmpty($this->getEntityManager()->getRepository(Teapot::class)->findAll());
    }
}
