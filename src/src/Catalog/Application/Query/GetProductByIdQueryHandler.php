<?php

namespace StockFlow\Catalog\Application\Query;

use Assert\Assert;
use StockFlow\Catalog\Application\Extractor\ProductExtractor;
use StockFlow\Catalog\Domain\Dto\ProductResponse;
use StockFlow\Catalog\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryHandlerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class GetProductByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductExtractor $extractor,
        private ProductRepositoryInterface $repository,
    ) {
    }

    public function __invoke(GetProductByIdQuery $query): ProductResponse
    {
        $product = $this->repository->findById($query->id);

        if (!$product) {
            throw new NotFoundHttpException('Продукт не существует');
        }

        return $this->extractor->extract($product);
    }
}
