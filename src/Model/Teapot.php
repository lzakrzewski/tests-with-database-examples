<?php

namespace Lzakrzewski\TestsWithDatabaseExamples\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="teapots")
 */
class Teapot
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $capacity;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $powerUse;

    /**
     * @ORM\OneToOne(targetEntity="Item", cascade={"all"})
     */
    private $item;

    /**
     * @param string $name
     * @param float  $price
     */
    public function __construct($name, $price)
    {
        $this->item = new Item($name, $price);

        $this->capacity = 1.2;
        $this->powerUse = 1200;
    }
}
