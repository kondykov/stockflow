<?php

namespace StockFlow\Catalog\Application\Extractor;

use StockFlow\Catalog\Domain\Dto\ProductResponse;
use StockFlow\Catalog\Domain\Entity\Product;
use StockFlow\Catalog\Domain\Entity\ProductAttribute;
use StockFlow\Catalog\Domain\Entity\ProductImage;
use StockFlow\Shared\Kernel\Application\Extractor\ExtractorInterface;

/**
 * @implements ExtractorInterface<Product, ProductResponse>
 */
readonly class ProductExtractor implements ExtractorInterface
{

    /**
     * @inheritDoc
     */
    public function extract(object $entity): ProductResponse
    {
        $attributes = array_map(static fn(ProductAttribute $attr) => [
            'key' => $attr->key,
            'value' => $attr->value
        ], $entity->attributes->toArray());

        $images = array_map(static fn(ProductImage $img) => [
            'id' => $img->id,
            'url' => $img->path,
            'isCover' => $img->isCover
        ], $entity->images->toArray());

        return new ProductResponse(
            id: $entity->id,
            name: $entity->name,
            skuCode: $entity->sku->code,
            skuName: $entity->sku->name,
            attributes: $attributes,
            images: $images,
            createdAt: $entity->createdAt?->format('Y-m-d H:i:s'),
            updatedAt: $entity->updatedAt?->format('Y-m-d H:i:s'),
        );
    }
}
