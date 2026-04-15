<?php

namespace StockFlow\Catalog\Application\Command;

use Assert\Assert;
use StockFlow\Catalog\Application\Extractor\ProductExtractor;
use StockFlow\Catalog\Domain\Dto\ProductResponse;
use StockFlow\Catalog\Domain\Entity\Product;
use StockFlow\Catalog\Domain\Entity\ProductImage;
use StockFlow\Catalog\Domain\Repository\ProductImageRepositoryInterface;
use StockFlow\Catalog\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Shared\Kernel\Application\Command\CommandHandlerInterface;
use StockFlow\Shared\Kernel\Domain\File\FileUploaderInterface;

readonly class UpdateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductExtractor $extractor,
        private ProductRepositoryInterface $repository,
        private ProductImageRepositoryInterface $imageRepository,
        private FileUploaderInterface $uploader,
    ) {
    }

    public function __invoke(UpdateProductCommand $command): ProductResponse
    {
        /** @var Product $product */
        $product = $this->repository->findById($command->id);
        Assert::that($product)->notNull('Продукт не найден');

        if ($product->sku->code !== $command->skuCode) {
            $exists = $this->repository->findBySkuCode($command->skuCode);
            Assert::that($exists)->null('Продукт с таким артикулом уже существует');
        }

        $product->name = $command->name;
        $product->sku = new Sku($command->skuCode, $command->skuName);

        if (!empty($command->attributes)) {
            $product->syncAttributes($command->attributes);
        }

        if (!empty($command->deletedImageIds)) {
            foreach ($command->deletedImageIds as $key => $imageId) {
                $image = $this->imageRepository->findById($imageId);
                $this->imageRepository->deleteById((int)$imageId);
                $this->uploader->remove($image->path);
                $product->removeImage($image->path);
            }
        }

        if (!empty($command->newImages)) {
            foreach ($command->newImages as $key => $imageFile) {
                if ($imageFile && method_exists($imageFile, 'getClientOriginalName')) {
                    $path = $this->uploader->upload($imageFile, 'products', isPublic: true);
                    $product->addImage($path);
                }
            }
        }

        /** @var ProductImage $image */
        foreach ($product->images as $key => $image) {
            $key === $command->coverIndex ? $image->isCover = true : $image->isCover = false;
        }

        $this->repository->save($product);

        return $this->extractor->extract($product);
    }
}
