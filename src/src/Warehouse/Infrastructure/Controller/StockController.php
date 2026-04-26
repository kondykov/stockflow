<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Infrastructure\Controller;

use OpenApi\Attributes as OA;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use StockFlow\Warehouse\Application\Command\Stock\AdjustmentStockCommand;
use StockFlow\Warehouse\Application\Command\Stock\IncomingStockItemCommand;
use StockFlow\Warehouse\Application\Command\Stock\OutgoingStockItemCommand;
use StockFlow\Warehouse\Application\Command\Stock\RemoveStockCommand;
use StockFlow\Warehouse\Application\Command\Stock\TransferStockCommand;
use StockFlow\Warehouse\Application\Query\GetAllStocksQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Warehouse — Stock')]
#[Route('/api/warehouse/{id}/stock', name: 'warehouse_stock_')]
class StockController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    #[Route(name: 'get_all', methods: ['GET'])]
    public function getAll(int $id, Request $request, QueryBusInterface $bus): JsonResponse
    {
        $query = new GetAllStocksQuery(
            id: $id,
            page: (int)$request->query->get('page', 1),
            pageSize: (int)$request->query->get('pageSize', 20),
        );

        $errors = $this->validator->validate($query);
        if (count($errors) > 0) {
            throw new ValidationFailedException($query, $errors);
        }

        return new JsonResponse($bus->execute($query));
    }

    private function validate(object $command): void
    {
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            throw new ValidationFailedException($command, $errors);
        }
    }

    #[Route('/incoming', name: 'incoming', methods: ['POST'])]
    public function incoming(int $id, Request $request, CommandBusInterface $bus): Response
    {
        $data = $request->toArray();

        $cmd = new IncomingStockItemCommand(
            warehouseId: $id,
            stockId: (int)($data['stockItemId'] ?? 0),
            quantity: (int)($data['quantity'] ?? 0),
        );

        $this->validate($cmd);

        return new JsonResponse($bus->execute($cmd), Response::HTTP_CREATED);
    }

    #[Route('/outgoing', name: 'outgoing', methods: ['PATCH'])]
    public function outgoing(int $id, Request $request, CommandBusInterface $bus): Response
    {
        $data = $request->toArray();

        $cmd = new OutgoingStockItemCommand(
            warehouseId: $id,
            stockId: (int)($data['stockItemId'] ?? 0),
            quantity: (int)($data['quantity'] ?? 0),
        );

        $this->validate($cmd);

        return new JsonResponse($bus->execute($cmd), Response::HTTP_OK);
    }

    #[Route('/adjust', name: 'adjust', methods: ['PATCH'])]
    public function adjust(int $id, Request $request, CommandBusInterface $bus): Response
    {
        $data = $request->toArray();

        $cmd = new AdjustmentStockCommand(
            warehouseId: $id,
            stockId: (int)($data['stockItemId'] ?? 0),
            quantity: (int)($data['quantity'] ?? 0),
        );

        $this->validate($cmd);

        return new JsonResponse($bus->execute($cmd), Response::HTTP_OK);
    }

    #[Route('/transfer', name: 'transfer', methods: ['POST'])]
    public function transfer(int $id, Request $request, CommandBusInterface $bus): Response
    {
        $data = $request->toArray();

        $cmd = new TransferStockCommand(
            fromWarehouseId: $id,
            toWarehouseId: (int)($data['toWarehouseId'] ?? 0),
            stockId: (int)($data['stockItemId'] ?? 0),
            quantity: (int)($data['quantity'] ?? 0),
            reason: $data['reason'] ?? null
        );

        $this->validate($cmd);

        return new JsonResponse($bus->execute($cmd), Response::HTTP_OK);
    }

    #[Route('/{stockId}', methods: ['DELETE'])]
    public function remove(
        int $id,
        int $stockId,
        CommandBusInterface $bus
    ): Response {
        $cmd = new RemoveStockCommand(
            warehouseId: $id,
            stockId: $stockId
        );

        $this->validate($cmd);

        return new JsonResponse($bus->execute($cmd), Response::HTTP_OK);
    }
}
