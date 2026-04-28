<?php

namespace StockFlow\Identity\Application\Command;

use StockFlow\Shared\Kernel\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Expression(
    "this.password === this.passwordConfirmation",
    message: "Пароли не совпадают"
)]
final readonly class CreateUserCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Имя не может быть пустым')]
        public string $name = '',
        #[Assert\Email(message: 'Необходимо ввести email')]
        #[Assert\NotBlank(message: 'Поле обязательно к заполнению')]
        public string $email = '',

        #[Assert\NotBlank(message: 'Пароль обязателен к заполнению')]
        #[Assert\Length(
            min: 3,
            max: 50,
            minMessage: 'Не менее {{ min }} символов',
            maxMessage: 'Не более {{ max }} символов'
        )]
        public string $password = '',

        #[Assert\NotBlank(message: 'Подтверждение пароля обязательно')]
        public string $passwordConfirmation = '',
    ) {
    }
}
