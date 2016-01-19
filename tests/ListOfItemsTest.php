<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests;

use Lucaszz\TestsWithDatabaseExamples\Entity\Beer;
use Lucaszz\TestsWithDatabaseExamples\Entity\Juice;
use Lucaszz\TestsWithDatabaseExamples\Entity\Teapot;
use Lucaszz\TestsWithDatabaseExamples\ListOfItems;

class ListOfItemsTest extends TestCase
{
    use \Lucaszz\TestsWithDatabaseExamples\Dictionary\MySqlDictionary;

    /** @test */
    public function it_can_have_list_of_items()
    {
        $this->givenDatabaseIsClear();

        $this->add(new Teapot('new-teapot', 100.3));
        $this->add(new Beer('tasty-beer', 4.1));
        $this->add(new Juice('orange', 1.3));

        $items = ListOfItems::create($this->getEntityManager())
            ->get(1, 10);

        $this->assertCount(3, $items);
    }

    /** @test */
    public function it_returns_empty_when_no_items()
    {
        $this->givenDatabaseIsClear();

        $items = ListOfItems::create($this->getEntityManager())
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

        ListOfItems::create($this->getEntityManager())
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
}
