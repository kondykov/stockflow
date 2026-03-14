<?php

namespace StockFlow\Identity\Application\Command;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Expression(
    "this.newPassword === this.newPasswordConfirmation",
    message: "Пароли не совпадают"
)]
class ChangePasswordCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Пароль обязателен к заполнению')]
        #[Assert\Length(
            min: 3,
            max: 50,
            minMessage: 'Не менее {{ min }} символов',
            maxMessage: 'Не более {{ max }} символов'
        )]
        public string $newPassword = '',

        #[Assert\NotBlank(message: 'Подтверждение пароля обязательно')]
        public string $newPasswordConfirmation = '',
    ) {
    }
}
