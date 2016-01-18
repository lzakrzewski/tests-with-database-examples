<?php

namespace Lucaszz\TestsWithDatabaseExamples\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Item
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $price;

    /**
     * @param string $name
     * @param float  $price
     */
    public function __construct($name, $price)
    {
        $this->name  = $name;
        $this->price = $price;
    }

    /**
     * @return array
     */
    public static function getClasses()
    {
        return [
            Apple::class,
            Beer::class,
            Blender::class,
            Glass::class,
            HairDryer::class,
            self::class,
            Juice::class,
            Mango::class,
            Phone::class,
            Teapot::class,
            Water::class,
        ];
    }
}
