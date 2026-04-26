<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Application\Query;

use StockFlow\Shared\Kernel\Application\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetStockItemsByIdsQuery implements QueryInterface
{
    /**
     * @param int[] $ids
     */
    public function __construct(
        #[Assert\All([
            new Assert\Type('int'),
            new Assert\Positive(message: "ID товара должен быть положительным"),
        ])]
        #[Assert\Count(
            min: 1,
            max: 100,
            minMessage: "Нужно передать хотя бы один ID товара",
            maxMessage: "Нельзя запрашивать больше 100 товаров за раз"
        )]
        public array $ids,

        #[Assert\Positive(message: "Номер страницы должен быть положительным")]
        public int $page = 1,

        #[Assert\Positive(message: "Размер страницы должен быть положительным")]
        public int $pageSize = 20,
    ) {
    }
}
