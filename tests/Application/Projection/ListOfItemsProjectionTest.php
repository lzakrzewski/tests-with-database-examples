<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Application\Projection;

use Carbon\Carbon;
use Lucaszz\TestsWithDatabaseExamples\Application\Projection\ListOfItemsProjection;
use Lucaszz\TestsWithDatabaseExamples\Model\Beer;
use Lucaszz\TestsWithDatabaseExamples\Model\Juice;
use Lucaszz\TestsWithDatabaseExamples\Model\Teapot;
use Lucaszz\TestsWithDatabaseExamples\Tests\TestCase;

class ListOfItemsProjectionTest extends TestCase
{
    /** @test */
    public function it_can_have_list_of_items()
    {
        $this->givenDatabaseIsClear();

        $this->add(new Teapot('new-teapot', 100));
        $this->add(new Beer('tasty-beer', 40));
        $this->add(new Juice('orange', 13));

        $items = ListOfItemsProjection::create($this->getEntityManager())
            ->get(1, 10);

        $this->assertCount(3, $items);
    }

    /** @test */
    public function it_can_have_list_sorted_with_the_smallest_price_first()
    {
        $this->givenDatabaseIsClear();

        $this->add(new Teapot('new-teapot', 100));
        $this->add(new Beer('tasty-beer', 40));
        $this->add(new Juice('orange', 13));

        $items = ListOfItemsProjection::create($this->getEntityManager())
            ->get(1, 10);

        $this->assertEquals('orange', $items[0]->name());
        $this->assertEquals('tasty-beer', $items[1]->name());
        $this->assertEquals('new-teapot', $items[2]->name());
    }

    /** @test */
    public function it_returns_empty_when_no_items()
    {
        $this->givenDatabaseIsClear();

        $items = ListOfItemsProjection::create($this->getEntityManager())
            ->get(1, 10);

        $this->assertEmpty($items);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidArguments
     */
    public function it_fails_with_invalid_arguments($page, $itemsPerPage)
    {
        $this->givenDatabaseIsClear();

        $this->add(new Teapot('new-teapot', 100.3));

        ListOfItemsProjection::create($this->getEntityManager())
            ->get($page, $itemsPerPage);
    }

    public function invalidArguments()
    {
        return [
            [0, 10],
            [1, 0],
            [-1, 10],
            [1, -10],
            ['test', 10],
            [1, 'test'],
        ];
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        Carbon::setTestNow(new Carbon());

        parent::setUp();
    }
}
