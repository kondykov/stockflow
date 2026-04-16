<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Warehouse\Application\Command\Stock\AdjustmentStockCommand;
use StockFlow\Warehouse\Application\Command\Stock\IncomingStockItemCommand;
use StockFlow\Warehouse\Application\Command\Stock\OutgoingStockItemCommand;
use StockFlow\Warehouse\Domain\ValueObject\StockResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Warehouse — Stock')]
#[Route('/api/warehouse/stock', name: 'warehouse_stock_')]
class StockController extends AbstractController
{
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
        #[MapRequestPayload] IncomingStockItemCommand $cmd,
        CommandBusInterface $bus
    ): Response {
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
        #[MapRequestPayload] OutgoingStockItemCommand $cmd,
        CommandBusInterface $bus
    ): Response {
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
        #[MapRequestPayload] AdjustmentStockCommand $cmd,
        CommandBusInterface $bus
    ): Response {
        return new JsonResponse($bus->execute($cmd), Response::HTTP_OK);
    }
}
