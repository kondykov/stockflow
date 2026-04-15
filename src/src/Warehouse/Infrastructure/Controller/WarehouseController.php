<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use StockFlow\Warehouse\Application\Command\CreateWarehouseCommand;
use StockFlow\Warehouse\Application\Query\GetAllWarehousesQuery;
use StockFlow\Warehouse\Application\Query\GetWarehouseByIdQuery;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Warehouse — Warehouses')]
#[Route('/api/warehouse', name: 'warehouse_')]
class WarehouseController extends AbstractController
{
    #[Route(name: 'create', methods: ['POST'])]
    #[OA\Post(
        summary: 'Создать склад',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: CreateWarehouseCommand::class)
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Склад успешно создан',
                content: new OA\JsonContent(
                    ref: new Model(type: WarehouseResponse::class)
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации или доменная ошибка',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'successful', type: 'boolean', example: false),
                    new OA\Property(property: 'error', type: 'string', example: 'Название склада не может быть пустым')
                ])
            )
        ]
    )]
    public function create(
        #[MapRequestPayload] CreateWarehouseCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($cmd), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'get_by_id', methods: ['GET'])]
    #[OA\Get(
        summary: 'Получить склад по ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Идентификатор склада',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Склад найден',
                content: new OA\JsonContent(
                    ref: new Model(type: WarehouseResponse::class)
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Склад не найден',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'successful', type: 'boolean', example: false),
                    new OA\Property(property: 'error', type: 'string', example: 'Склад не найден')
                ])
            )
        ]
    )]
    public function getById(
        int $id,
        QueryBusInterface $bus,
    ): JsonResponse {
        $query = new GetWarehouseByIdQuery($id);

        return new JsonResponse($bus->execute($query));
    }

    #[Route("/", name: 'all', methods: ['GET'])]
    public function getAll(
        #[MapQueryString] GetAllWarehousesQuery $query,
        QueryBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($query));
    }
}
