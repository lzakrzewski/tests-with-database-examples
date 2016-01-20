<?php

namespace Lucaszz\TestsWithDatabaseExamples\Application\UseCase;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Lucaszz\TestsWithDatabaseExamples\Model\Item;

final class ApplyDiscountUseCase
{
    /** @var ObjectRepository */
    private $items;
    /** @var ObjectManager */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    private function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->items         = $objectManager->getRepository(Item::class);
    }

    /**
     * @param ObjectManager $objectManager
     *
     * @return ApplyDiscountUseCase
     */
    public static function create(ObjectManager $objectManager)
    {
        return new self($objectManager);
    }

    /**
     * @param float $discount
     */
    public function apply($discount)
    {
        foreach ($this->items->findAll() as $item) {
            $item->applyDiscount($discount);
        }

        $this->objectManager->flush();
    }
}
