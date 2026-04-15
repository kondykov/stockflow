<?php

namespace StockFlow\Catalog\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use StockFlow\Shared\Catalog\Domain\ValueObject\Sku;
use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;

class Product
{
    use TimeStamps;

    public private(set) ?int $id = null;
    public private(set) Collection $attributes;
    public private(set) Collection $images;

    public function __construct(
        public string $name,
        public Sku $sku,
    ) {
        $this->attributes = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function syncAttributes(array $data): void
    {
        $this->attributes->clear();
        foreach ($data as $item) {
            $key = $item['key'] ?? null;
            $value = $item['value'] ?? null;

            if ($key && $value) {
                $this->attributes->add(new ProductAttribute($this, (string)$key, (string)$value));
            }
        }
    }

    public function addImage(string $path, bool $isCover = false): void
    {
        if ($isCover) {
            /** @var ProductImage $image */
            foreach ($this->images as $image) {
                $image->isCover = false;
            }
        }

        $this->images->add(new ProductImage($this, $path, $isCover));
    }

    public function removeImage(string $path): void
    {
        foreach ($this->images as $key => $image) {
            if ($image->path === $path) {
                $this->images->remove($key);
                break;
            }
        }
    }

    public function clearImages(): void
    {
        $this->images->clear();
    }
}
