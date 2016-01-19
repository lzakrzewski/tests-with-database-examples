<?php

namespace Lucaszz\TestsWithDatabaseExamples\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap(
 *      {
 *          "apple" = "Apple",
 *          "beer" = "Beer",
 *          "blender" = "Blender",
 *          "glass" = "Glass",
 *          "hairDryer" = "HairDryer",
 *          "juice" = "Juice",
 *          "mango" = "Mango",
 *          "phone" = "Phone",
 *          "teapot" = "Teapot",
 *          "water" = "Water",
 *      }
 *  )
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
