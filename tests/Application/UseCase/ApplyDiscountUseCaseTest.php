<?php

namespace Lzakrzewski\TestsWithDatabaseExamples\Tests\Application\UseCase;

use Lzakrzewski\TestsWithDatabaseExamples\Application\UseCase\ApplyDiscountUseCase;
use Lzakrzewski\TestsWithDatabaseExamples\Model\Beer;
use Lzakrzewski\TestsWithDatabaseExamples\Model\Juice;
use Lzakrzewski\TestsWithDatabaseExamples\Model\Teapot;
use Lzakrzewski\TestsWithDatabaseExamples\Tests\TestCase;

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
