<?php

namespace Lucaszz\TestsWithDatabaseExamples\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="teapots")
 */
class Teapot extends Item
{
}
