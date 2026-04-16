<?php

namespace StockFlow\Warehouse\Domain\Aggregate;

use Assert\Assert;
use Assert\LazyAssertionException;
use StockFlow\Shared\Kernel\Domain\Aggregate\AggregateRoot;
use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\Event\StockIncomingRecorded;
use StockFlow\Warehouse\Domain\Event\StockMovementRecorded;
use StockFlow\Warehouse\Domain\Event\StockOutgoingRecorded;

/**
 * Класс Stock представляет собой агрегат, который управляет количеством товара на складе.
 * Он содержит методы для отгрузки, получения и корректировки количества товара на складе.
 */
class Stock extends AggregateRoot
{
    use TimeStamps;

    public private(set) ?int $id = null;

    public private(set) int $onHands = 0;

    public function __construct(
        public Warehouse $warehouse,
        public StockItem $item,
        ?int $quantity = 0,
    ) {
        $this->onHands = $quantity;
    }

    /**
     * Отгрузка товара со склада. Уменьшает количество на складе на указанное количество.
     *
     * @param int $quantity Количество для отгрузки
     * @return static
     * @throws LazyAssertionException Если количество для отгрузки больше, чем есть на складе, или если количество не положительное
     * @throws LazyAssertionException Если количество не соответствует требованиям валидации
     */
    public function deduct(int $quantity): static
    {
        Assert::lazy()
            ->that($quantity, 'quantity')
            ->greaterThan(0, 'Количество должно быть положительным')
            ->lessOrEqualThan($this->onHands, 'Невозможно отгрузить больше, чем есть на складе')
            ->verifyNow();

        $this->record(new StockOutgoingRecorded(
            warehouseId: $this->warehouse->id,
            productId: $this->item->id,
            quantity: $quantity,
            aggregateId: $this->id ?? 0,
        ));

        $this->onHands -= $quantity;

        return $this;
    }

    /**
     * Получение товара на склад. Увеличивает количество на складе на указанное количество.
     *
     * @param int $quantity Количество для получения
     * @return static
     * @throws LazyAssertionException Если количество для получения не положительное
     */
    public function receive(int $quantity): static
    {
        Assert::lazy()
            ->that($quantity, 'quantity')
            ->greaterThan(0, 'Количество должно быть положительным')
            ->verifyNow();

        $this->record(new StockIncomingRecorded(
            warehouseId: $this->warehouse->id,
            productId: $this->item->id,
            quantity: $quantity,
            aggregateId: $this->id ?? 0,
        ));

        $this->onHands += $quantity;

        return $this;
    }

    /**
     * Корректировка количества товара на складе. Устанавливает количество на складе на указанное количество.
     *
     * @param int $quantity Новое количество на складе
     * @return static
     */
    public function adjust(int $quantity): static
    {
        Assert::lazy()
            ->that($quantity, 'quantity')
            ->greaterOrEqualThan(0, 'Количество не может быть отрицательным')
            ->verifyNow();

        $this->record(new StockMovementRecorded(
            warehouseId: $this->warehouse->id,
            productId: $this->item->id,
            oldQuantity: $this->onHands,
            newQuantity: $quantity,
            aggregateId: $this->id ?? 0,
        ));

        $this->onHands = $quantity;

        return $this;
    }
}
