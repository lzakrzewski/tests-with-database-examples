<?php

namespace Lucaszz\TestsWithDatabaseExamples;

use Assert\Assertion;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Lucaszz\TestsWithDatabaseExamples\Entity\Item;

final class ListOfItems
{
    /** @var ObjectRepository */
    private $items;

    /**
     * @param ObjectManager $objectManager
     */
    private function __construct(ObjectManager $objectManager)
    {
        $this->items = $objectManager->getRepository(Item::class);
    }

    /**
     * @param ObjectManager $objectManager
     *
     * @return ListOfItems
     */
    public static function create(ObjectManager $objectManager)
    {
        return new self($objectManager);
    }

    /**
     * @param $page
     * @param $itemsPerPage
     *
     * @throws \InvalidArgumentException
     *
     * @return Item[]
     */
    public function get($page, $itemsPerPage)
    {
        Assertion::integer($page, 'Page should be an integer');
        Assertion::integer($itemsPerPage, 'ItemsPerPage should be an integer');
        Assertion::greaterThan($page, 0, 'Page should be grater than 0');
        Assertion::greaterThan($itemsPerPage, 0, 'ItemsPerPage should be grater than 0');

        return array_slice($this->items->findAll(), $page - 1, $itemsPerPage);
    }
}