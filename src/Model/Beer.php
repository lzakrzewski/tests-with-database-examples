<?php

namespace Lzakrzewski\TestsWithDatabaseExamples\Model;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="beers")
 */
class Beer
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expirationDate;

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

        $this->brand          = 'Teasty beer';
        $this->expirationDate = Carbon::now()->addDays(155);
    }
}
