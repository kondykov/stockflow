<?php

namespace StockFlow\Identity\Application\Command\RBAC;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class BatchUpdateRolesCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Идентификатор пользователя обязателен')]
        #[Assert\Type('int', message: 'Идентификатор должен быть целым числом')]
        public int $userId,

        #[Assert\Type('array', message: 'Роли должны быть массивом')]
        #[Assert\All(
            constraints: [
                new Assert\Type('string', message: 'Каждая роль должна быть строкой'),
                new Assert\NotBlank(message: 'Роль не может быть пустой')
            ]
        )]
        public array $roles,
    ) {
    }
}
