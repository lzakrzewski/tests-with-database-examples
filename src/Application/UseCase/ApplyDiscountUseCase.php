<?php

namespace Lzakrzewski\TestsWithDatabaseExamples\Application\UseCase;

use Doctrine\ORM\EntityManager;
use Lzakrzewski\TestsWithDatabaseExamples\Application\Persistence\ItemRepository;

final class ApplyDiscountUseCase
{
    /** @var ItemRepository */
    private $items;
    /** @var EntityManager */
    private $entityManager;

    /**
     * @param EntityManager $objectManager
     */
    private function __construct(EntityManager $objectManager)
    {
        $this->entityManager = $objectManager;
        $this->items         = ItemRepository::create($objectManager);
    }

    /**
     * @param EntityManager $objectManager
     *
     * @return ApplyDiscountUseCase
     */
    public static function create(EntityManager $objectManager)
    {
        return new self($objectManager);
    }

    /**
     * @param float $discount
     */
    public function apply($discount)
    {
        $this->entityManager->transactional(function () use ($discount) {
            foreach ($this->items->findAll() as $item) {
                $item->applyDiscount($discount);
            }
        });

        $this->entityManager->clear();
    }
}
