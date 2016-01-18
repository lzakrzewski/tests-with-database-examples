<?php

namespace Lucaszz\TestsWithDatabaseExamples\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Lucaszz\TestsWithDatabaseExamples\Entity\Item;

class LoadItems extends AbstractFixture
{
    /** {@inheritdoc} */
    public function load(ObjectManager $manager)
    {
        foreach (Item::getClasses() as $class) {
            if ($class == Item::class) {
                continue;
            }

            for ($itemOfTypeIdx = 1; $itemOfTypeIdx <= 5; ++$itemOfTypeIdx) {
                $item = new $class($this->itemName($class, $itemOfTypeIdx), rand(1, 1000) / 10);
                $manager->persist($item);
            }

            $manager->flush();
        }
    }

    private function itemName($class, $itemOfTypeIdx)
    {
        $reflection = new \ReflectionClass($class);

        return sprintf('%s_%d', $reflection->getShortName(), $itemOfTypeIdx);
    }
}
