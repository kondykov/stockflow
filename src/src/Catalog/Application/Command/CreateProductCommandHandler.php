<?php

namespace StockFlow\Catalog\Application\Command;

use Assert\Assert;
use StockFlow\Catalog\Application\Extractor\ProductExtractor;
use StockFlow\Catalog\Domain\Dto\ProductResponse;
use StockFlow\Catalog\Domain\Entity\Product;
use StockFlow\Catalog\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Shared\Kernel\Domain\File\FileUploaderInterface;

readonly class CreateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductExtractor $extractor,
        private ProductRepositoryInterface $repository,
        private FileUploaderInterface $uploader,
    ) {
    }

    public function __invoke(CreateProductCommand $command): ProductResponse
    {
        $exists = $this->repository->findBySkuCode($command->skuCode);
        Assert::that($exists)
            ->null('Продукт с таким артикулом уже существует');

        $product = new Product(
            name: $command->name,
            sku: new Sku($command->skuCode, $command->skuName),
        );

        if (!empty($command->attributes)) {
            $product->syncAttributes($command->attributes);
        }

        if (!empty($command->images)) {
            foreach ($command->images as $imageData) {
                $file = $imageData['file'] ?? null;
                $isCover = $imageData['isCover'] ?? false;

                if ($file && method_exists($file, 'getClientOriginalName')) {
                    $path = $this->uploader->upload($file, 'products', isPublic: true);
                    $product->addImage($path, $isCover);
                }
            }
        }

        $this->repository->save($product);

        return $this->extractor->extract($product);
    }
}
