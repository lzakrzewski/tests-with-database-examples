<?php

namespace Lucaszz\TestsWithDatabaseExamples\Model;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="blenders")
 */
class Blender
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
    private $powerUse;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $brand;

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

        $this->brand          = 'Unitra';
        $this->expirationDate = Carbon::now()->addDays(155);
    }
}
