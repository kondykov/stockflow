<?php

namespace StockFlow\Catalog\Application\Command;

use Assert\Assert;
use StockFlow\Catalog\Application\Extractor\ProductExtractor;
use StockFlow\Catalog\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;

final readonly class UpdateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductExtractor $extractor,
        private ProductRepositoryInterface $repository,
    ) {
    }

    public function __invoke(UpdateProductCommand $command): mixed
    {
        $product = $this->repository->findById($command->id);

        Assert::that($product, 'Продукт не найден')->notNull();

        if ($product->sku->code !== $command->skuCode) {
            $exists = $this->repository->findBySkuCode($command->skuCode);
            Assert::that($exists, 'Артикул ' . $command->skuCode . ' уже занят')->null();
        }

        $product->name = $command->name;

        $product->sku = new Sku(
            code: $command->skuCode,
            name: $command->skuName
        );

        $this->repository->save($product);

        return $this->extractor->extract($product);
    }
}
