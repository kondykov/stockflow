<?php

namespace StockFlow\Shared\Kernel\Domain\Trait;


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
