<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Application\UseCase;

use Lucaszz\TestsWithDatabaseExamples\Application\UseCase\ApplyDiscountUseCase;
use Lucaszz\TestsWithDatabaseExamples\Model\Beer;
use Lucaszz\TestsWithDatabaseExamples\Model\Juice;
use Lucaszz\TestsWithDatabaseExamples\Model\Teapot;
use Lucaszz\TestsWithDatabaseExamples\Tests\TestCase;

class ApplyDiscountUseCaseTest extends TestCase
{
    /** @test */
    public function it_applies_discount_on_items()
    {
        $this->givenDatabaseIsClear();

        $this->add(new Teapot('new-teapot', 100));
        $this->add(new Beer('tasty-beer', 50));
        $this->add(new Juice('orange', 30));

        ApplyDiscountUseCase::create($this->getEntityManager())
            ->apply(0.5);

        $this->assertEquals(50, $this->findItemByName('new-teapot')->price());
        $this->assertEquals(25, $this->findItemByName('tasty-beer')->price());
        $this->assertEquals(15, $this->findItemByName('orange')->price());
    }
}
