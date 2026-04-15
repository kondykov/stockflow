<?php

namespace StockFlow\Identity\Application\Command\RBAC;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;

final readonly class UpdateRoleCommand implements CommandInterface
{
    public function __construct(
        public ?int $id,
        public string $name,
        public array $permissions,
    ) {
    }
}
