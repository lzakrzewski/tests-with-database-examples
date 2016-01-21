<?php

namespace Lucaszz\TestsWithDatabaseExamples\Component\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Lucaszz\TestsWithDatabaseExamples\Component\Mapping;
use Lucaszz\TestsWithDatabaseExamples\Model;

class LoadItems extends AbstractFixture
{
    const COUNT = 10;

    /** @var array */
    private $prices = [
        Model\Apple::class     => 5,
        Model\Beer::class      => 7,
        Model\Blender::class   => 500,
        Model\Glass::class     => 2,
        Model\HairDryer::class => 120,
        Model\Juice::class     => 3,
        Model\Mango::class     => 4,
        Model\Phone::class     => 400,
        Model\Teapot::class    => 100,
        Model\Water::class     => 2,
    ];

    /** {@inheritdoc} */
    public function load(ObjectManager $manager)
    {
        foreach (Mapping::mappedClasses() as $class) {
            if ($class == Model\Item::class) {
                continue;
            }

            for ($itemOfTypeIdx = 1; $itemOfTypeIdx <= self::COUNT / 10; ++$itemOfTypeIdx) {
                $item = new $class($this->itemName($class, $itemOfTypeIdx), $this->prices[$class]);
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
