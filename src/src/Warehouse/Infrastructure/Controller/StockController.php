<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use StockFlow\Warehouse\Application\Command\Stock\AdjustmentStockCommand;
use StockFlow\Warehouse\Application\Command\Stock\IncomingStockItemCommand;
use StockFlow\Warehouse\Application\Command\Stock\OutgoingStockItemCommand;
use StockFlow\Warehouse\Application\Query\GetAllStocksQuery;
use StockFlow\Warehouse\Domain\ValueObject\StockItemResponse;
use StockFlow\Warehouse\Domain\ValueObject\StockResponse;
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
    #[OA\Get(
        summary: 'Получить список продуктов по складу',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID склада',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Номер страницы',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
            new OA\Parameter(
                name: 'pageSize',
                description: 'Размер страницы',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 20)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список продуктов',
                content: new OA\JsonContent(
                    ref: new Model(type: StockItemResponse::class)
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации или доменная ошибка',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'successful', type: 'boolean', example: false),
                    new OA\Property(property: 'error', type: 'string', example: 'Некорректный ID склада')
                ])
            )
        ]
    )]
    public function getAll(
        int $id,
        Request $request,
        QueryBusInterface $bus,
    ): JsonResponse {
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

    #[Route('/incoming', name: 'incoming', methods: ['POST'])]
    #[OA\Post(
        summary: 'Приход товара на склад',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: IncomingStockItemCommand::class)
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Остаток обновлён (приход)',
                content: new OA\JsonContent(
                    ref: new Model(type: StockResponse::class)
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации или доменная ошибка',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'successful', type: 'boolean', example: false),
                    new OA\Property(property: 'error', type: 'string',
                        example: 'Количество должно быть положительным числом')
                ])
            )
        ]
    )]
    public function incoming(
        int $id,
        Request $request,
        CommandBusInterface $bus
    ): Response {
        $cmd = new IncomingStockItemCommand(
            warehouseId: $id,
            stockItemId: $request->request->get('stockItemId'),
            quantity: $request->request->get('quantity'),
        );

        $errors = $this->validator->validate($cmd);
        if (count($errors) > 0) {
            throw new ValidationFailedException($cmd, $errors);
        }

        return new JsonResponse($bus->execute($cmd), Response::HTTP_CREATED);
    }

    #[Route('/outgoing', name: 'outgoing', methods: ['PATCH'])]
    #[OA\Patch(
        summary: 'Расход товара со склада',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: OutgoingStockItemCommand::class)
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Остаток обновлён (расход)',
                content: new OA\JsonContent(
                    ref: new Model(type: StockResponse::class)
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации или доменная ошибка',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'successful', type: 'boolean', example: false),
                    new OA\Property(property: 'error', type: 'string', example: 'Недостаточно товара на складе')
                ])
            )
        ]
    )]
    public function outgoing(
        int $id,
        Request $request,
        CommandBusInterface $bus
    ): Response {
        $cmd = new OutgoingStockItemCommand(
            warehouseId: $id,
            stockItemId: $request->request->get('stockItemId'),
            quantity: $request->request->get('quantity'),
        );

        $errors = $this->validator->validate($cmd);
        if (count($errors) > 0) {
            throw new ValidationFailedException($cmd, $errors);
        }

        return new JsonResponse($bus->execute($cmd), Response::HTTP_OK);
    }

    #[Route('/adjust', name: 'adjust', methods: ['PATCH'])]
    #[OA\Patch(
        summary: 'Корректировка остатка',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: AdjustmentStockCommand::class)
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Остаток скорректирован',
                content: new OA\JsonContent(
                    ref: new Model(type: StockResponse::class)
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации или доменная ошибка',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'successful', type: 'boolean', example: false),
                    new OA\Property(property: 'error', type: 'string', example: 'Нельзя установить остаток меньше нуля')
                ])
            )
        ]
    )]
    public function adjust(
        int $id,
        Request $request,
        CommandBusInterface $bus
    ): Response {
        $cmd = new AdjustmentStockCommand(
            warehouseId: $id,
            stockItemId: $request->request->get('stockItemId'),
            quantity: $request->request->get('quantity'),
        );

        $errors = $this->validator->validate($cmd);
        if (count($errors) > 0) {
            throw new ValidationFailedException($cmd, $errors);
        }

        return new JsonResponse($bus->execute($cmd), Response::HTTP_OK);
    }
}
