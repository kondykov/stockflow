<?php

namespace StockFlow\Shared\Domain\Repository;

interface RepositoryInterface
{
    public function save(object $entity): void;
    public function remove(object $entity): void;
    public function findById(int|string $id): ?object;
}
