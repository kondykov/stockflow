<?php

namespace StockFlow\Identity\Application\Command\RBAC;

use StockFlow\Shared\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;


final readonly class AssignRoleCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Идентификатор пользователя обязателен')]
        #[Assert\Type('int', message: 'Идентификатор должен быть целым числом')]
        public int $userId,
        #[Assert\NotBlank(message: 'Имя роли не должно быть пустым')]
        #[Assert\Type('string', message: 'Имя роли должно быть строкой')]
        public string $role,
    ) {
    }
}
