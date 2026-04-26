<?php

namespace StockFlow\Warehouse\Domain\Aggregate;

use Assert\Assert;
use StockFlow\Shared\Kernel\Domain\Aggregate\AggregateRoot;
use StockFlow\Shared\Kernel\Domain\Trait\TimeStamps;
use StockFlow\Warehouse\Domain\Entity\StockItem;
use StockFlow\Warehouse\Domain\Entity\Warehouse;
use StockFlow\Warehouse\Domain\Event\StockIncomingEvent;
use StockFlow\Warehouse\Domain\Event\StockMovementEvent;
use StockFlow\Warehouse\Domain\Event\StockOutgoingEvent;

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
     * @param string|null $correlationId
     * @return static
     */
    public function deduct(int $quantity, ?string $correlationId = null): static
    {
        Assert::lazy()
            ->that($quantity, 'quantity')
            ->greaterThan(0, 'Количество должно быть положительным')
            ->lessOrEqualThan($this->onHands, 'Невозможно отгрузить больше, чем есть на складе')
            ->verifyNow();

        $this->record(new StockOutgoingEvent(
            warehouseId: $this->warehouse->id,
            stockItemId: $this->item->id,
            quantity: $quantity,
            aggregateId: $this->id ?? 0,
            correlationId: $correlationId
        ));

        $this->onHands -= $quantity;
        return $this;
    }

    /**
     * Получение товара на склад. Увеличивает количество на складе на указанное количество.
     *
     * @param int $quantity Количество для получения
     * @param string|null $correlationId
     * @return static
     */
    public function receive(int $quantity, ?string $correlationId = null): static
    {
        Assert::lazy()
            ->that($quantity, 'quantity')
            ->greaterThan(0, 'Количество должно быть положительным')
            ->verifyNow();

        $this->record(new StockIncomingEvent(
            warehouseId: $this->warehouse->id,
            stockItemId: $this->item->id,
            quantity: $quantity,
            aggregateId: $this->id ?? 0,
            correlationId: $correlationId
        ));

        $this->onHands += $quantity;

        return $this;
    }

    /**
     * Корректировка количества товара на складе. Устанавливает количество на складе на указанное количество.
     *
     * @param int $quantity Новое количество на складе
     * @param string|null $correlationId
     * @return static
     */
    public function adjust(int $quantity, ?string $correlationId = null): static
    {
        Assert::lazy()
            ->that($quantity, 'quantity')
            ->greaterOrEqualThan(0, 'Количество не может быть отрицательным')
            ->verifyNow();

        $this->record(new StockMovementEvent(
            warehouseId: $this->warehouse->id,
            stockItemId: $this->item->id,
            oldQuantity: $this->onHands,
            newQuantity: $quantity,
            aggregateId: $this->id ?? 0,
            correlationId: $correlationId
        ));

        $this->onHands = $quantity;

        return $this;
    }
}
