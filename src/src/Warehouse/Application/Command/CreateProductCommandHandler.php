<?php

namespace StockFlow\Warehouse\Application\Command;

use Assert\Assert;
use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Warehouse\Application\Extractor\StockItemExtractor;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\Repository\StockItemRepositoryInterface;
use StockFlow\Warehouse\Domain\ValueObject\StockItemResponse;

readonly class CreateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StockItemExtractor $extractor,
        private StockItemRepositoryInterface $repository,
    ) {
    }

    public function __invoke(CreateProductCommand $command): StockItemResponse
    {
        $exists = $this->repository->findBySkuCode($command->code);

        Assert::that($exists, 'Продукт с таким артикулом уже существует', 'code')
            ->null();

        $product = new StockItem(
            sku: new Sku($command->code, $command->name),
            remoteId: $command->remoteId,
        );

        $this->repository->save($product);

        return $this->extractor->extract($product);
    }
}
