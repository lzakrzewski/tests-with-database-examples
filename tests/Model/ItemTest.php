<?php

namespace Lucaszz\TestsWithDatabaseExamples\Tests\Model;

use Lucaszz\TestsWithDatabaseExamples\Model\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_have_discount_applied()
    {
        $item = new Item('test', 100);

        $item->applyDiscount(0.5);

        $this->assertEquals(50, $item->price());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_fails_when_discount_is_wrong()
    {
        $item = new Item('test', 100);

        $item->applyDiscount('ukulele');
    }
}
