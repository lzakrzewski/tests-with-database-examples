<?php

namespace Lzakrzewski\TestsWithDatabaseExamples\Tests\Examples;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Lzakrzewski\TestsWithDatabaseExamples\Application\Persistence\ItemRepository;
use Lzakrzewski\TestsWithDatabaseExamples\Model\Phone;
use Lzakrzewski\TestsWithDatabaseExamples\Model\Teapot;
use Lzakrzewski\TestsWithDatabaseExamples\Tests\TestCase;

class RepositoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider items
     */
    public function purge_database()
    {
        $this->purgeDatabase();

        $this->add(new Teapot('brand-new-teapot', 10.0));
        $this->add(new Phone('amazing-phone', 400.0));

        $items = ItemRepository::create($this->getEntityManager())
            ->paginate(1, 2);

        $this->assertCount(2, $items);
    }

    /**
     * @test
     * @dataProvider items
     */
    public function reverting_transaction()
    {
        $this->givenDatabaseIsClear();
        $this->getEntityManager()->beginTransaction();

        $this->add(new Teapot('brand-new-teapot', 10.0));
        $this->add(new Phone('amazing-phone', 400.0));

        $items = ItemRepository::create($this->getEntityManager())
            ->paginate(1, 2);

        $this->assertCount(2, $items);

        $this->getEntityManager()->rollback();
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }
}
