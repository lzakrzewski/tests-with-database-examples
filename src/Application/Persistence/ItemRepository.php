<?php

namespace Lucaszz\TestsWithDatabaseExamples\Application\Persistence;

use Assert\Assertion;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Lucaszz\TestsWithDatabaseExamples\Model\Item;

class ItemRepository extends EntityRepository
{
    /**
     * @param EntityManager $objectManager
     *
     * @return ItemRepository
     */
    public static function create(EntityManager $objectManager)
    {
        return $objectManager->getRepository(Item::class);
    }

    /**
     * @param $page
     * @param $itemsPerPage
     *
     * @throws \InvalidArgumentException
     *
     * @return Item[]
     */
    public function paginate($page, $itemsPerPage)
    {
        Assertion::integer($page, 'Page should be an integer');
        Assertion::integer($itemsPerPage, 'ItemsPerPage should be an integer');
        Assertion::greaterThan($page, 0, 'Page should be grater than 0');
        Assertion::greaterThan($itemsPerPage, 0, 'ItemsPerPage should be grater than 0');

        return array_slice($this->findBy([], ['price' => 'ASC']), $page - 1, $itemsPerPage);
    }
}
