<?php

namespace Lucaszz\TestsWithDatabaseExamples\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="apples")
 */
class Apple extends Item
{
}