<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Infrastructure\Controller;

use Illuminate\Validation\ValidationException;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use StockFlow\Warehouse\Application\Command\CreateWarehouseCommand;
use StockFlow\Warehouse\Application\Command\DeleteWarehouseCommand;
use StockFlow\Warehouse\Application\Command\UpdateWarehouseCommand;
use StockFlow\Warehouse\Application\Query\GetAllWarehousesQuery;
use StockFlow\Warehouse\Application\Query\GetWarehouseByIdQuery;
use StockFlow\Warehouse\Domain\ValueObject\WarehouseResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[OA\Put(
        summary: 'Обновить склад',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: new Model(type: UpdateWarehouseCommand::class))
        ),
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Склад обновлен', content: new OA\JsonContent(ref: new Model(type: WarehouseResponse::class))),
            new OA\Response(response: 400, description: 'Ошибка валидации'),
            new OA\Response(response: 404, description: 'Склад не найден или нет доступа'),
        ]
    )]
    public function update(
        int $id,
        Request $request,
        ValidatorInterface $validator,
        CommandBusInterface $bus
    ): JsonResponse {
        $cmd = new UpdateWarehouseCommand(
            id: $id,
            name: $request->request->get('name'),
            address: $request->request->get('address')
        );

        $errors = $validator->validate($cmd);
        if (count($errors) > 0) {
            throw new ValidationFailedException($cmd, $errors);
        }

        return new JsonResponse($bus->execute($cmd));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Удалить склад',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Склад удален'),
            new OA\Response(response: 404, description: 'Склад не найден или нет доступа'),
        ]
    )]
    public function delete(
        int $id,
        CommandBusInterface $bus
    ): JsonResponse {
        $cmd = new DeleteWarehouseCommand($id);
        $bus->execute($cmd);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
