<?php

namespace StockFlow\Shared\Domain\Trait;

use Doctrine\ORM\Mapping as ORM;


trait Identifiable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) ?int $id = null;
}
