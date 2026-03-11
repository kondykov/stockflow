<?php

namespace StockFlow\Shared\Domain\Trait;

use Doctrine\ORM\Mapping as ORM;


trait TimeStamps
{
    public \DateTimeImmutable $createdAt;
    public \DateTimeImmutable $updatedAt;

    public function setInitialTimestamps(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setUpdateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
