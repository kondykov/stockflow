<?php

namespace StockFlow\Shared\Kernel\Infrastructure\Response;

final readonly class ApiResponse
{
    public function __construct(
        public bool $successful,
        public ?string $message = null,
        public mixed $data = null,
    ) {}

    public static function success(mixed $data = null, ?string $message = null): self
    {
        return new self(true, $message, $data);
    }

    public static function error(string $message, mixed $data = null): self
    {
        return new self(false, $message, $data);
    }
}
