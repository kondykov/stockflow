<?php

namespace StockFlow\Warehouse\Application\Command;

use Assert\Assert;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\ProductExtractor;
use StockFlow\Warehouse\Application\Extractor\WarehouseExtractor;
use StockFlow\Warehouse\Domain\Entity\Product;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Warehouse\Domain\Repository\WarehouseRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\ProductResponse;
use StockFlow\Warehouse\Domain\ValueObject\Sku;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;

readonly class CreateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductExtractor $extractor,
        private ProductRepositoryInterface $repository,
    ) {
    }

    public function __invoke(CreateProductCommand $command): ProductResponse
    {
        $exists = $this->repository->findBySkuCode($command->code);

        Assert::that($exists, 'Продукт с таким артикулом уже существует', 'code')
            ->null();

        $product = new Product(
            sku: new Sku($command->code, $command->name),
            remoteId: $command->remoteId,
        );

        $this->repository->save($product);

        return $this->extractor->extract($product);
    }
}
