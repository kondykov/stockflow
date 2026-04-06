<?php

namespace StockFlow\Identity\Application\Command\RBAC;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateNewRoleCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Имя роли не должно быть пустым')]
        #[Assert\Type('string', message: 'Имя роли должно быть строкой')]
        public string $name,

        #[Assert\NotBlank(message: 'Список прав не может быть пустым')]
        #[Assert\Type('array', message: 'Права должны быть массивом')]
        #[Assert\All([
            new Assert\Type(type: 'string', message: 'Пермишен должен быть строкой'),
            new Assert\Choice(
                callback: 'StockFlow\Shared\Identity\Domain\Enum\RBAC\Permission::values',
                message: 'Выбран неверный пермишен: {{ value }}'
            )
        ])]
        public array $permissions
    ) {
    }
}
