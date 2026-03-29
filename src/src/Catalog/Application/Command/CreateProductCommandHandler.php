<?php

namespace StockFlow\Catalog\Application\Command;

use Assert\Assert;
use StockFlow\Catalog\Application\Extractor\ProductExtractor;
use StockFlow\Catalog\Domain\Entity\Product;
use StockFlow\Catalog\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;

readonly class CreateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductExtractor $extractor,
        private ProductRepositoryInterface $repository,
    ) {
    }

    public function __invoke(CreateProductCommand $command): mixed
    {
        $exists = $this->repository->findBySkuCode($command->skuCode);

        Assert::that($exists, 'Продукт с таким артикулом уже существует', 'skuCode')
            ->null();

        $product = new Product(
            name: $command->name,
            sku: new Sku($command->skuCode, $command->skuName),
        );

        $this->repository->save($product);

        return $this->extractor->extract($product);
    }
}
