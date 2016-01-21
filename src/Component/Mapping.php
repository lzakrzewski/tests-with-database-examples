<?php

namespace Lucaszz\TestsWithDatabaseExamples\Component;

use Lucaszz\TestsWithDatabaseExamples\Model;

final class Mapping
{
    private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function mappedClasses()
    {
        return [
            Model\Apple::class,
            Model\Beer::class,
            Model\Blender::class,
            Model\Glass::class,
            Model\HairDryer::class,
            Model\Item::class,
            Model\Juice::class,
            Model\Mango::class,
            Model\Phone::class,
            Model\Teapot::class,
            Model\Water::class,
        ];
    }
}
