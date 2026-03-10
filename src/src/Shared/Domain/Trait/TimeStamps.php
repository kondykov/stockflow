<?php

namespace StockFlow\Shared\Domain\Trait;

use Doctrine\ORM\Mapping as ORM;


trait TimeStamps
{
    #[ORM\Column(type: 'datetime_immutable')]
    public private(set) \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    public private(set) \DateTimeImmutable $updatedAt;

    #[ORM\PrePersist]
    public function setInitialTimestamps(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
