<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use StockFlow\Warehouse\Application\Command\CreateProductCommand;
use StockFlow\Warehouse\Application\Query\GetAllProductsQuery;
use StockFlow\Warehouse\Domain\ValueObject\StockItemResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Warehouse — Products')]
#[Route('/api/warehouse/product', name: 'warehouse_product_')]
class ProductController extends AbstractController
{
    #[Route(name: 'create', methods: ['POST'])]
    #[OA\Post(
        summary: 'Создать продукт',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: CreateProductCommand::class)
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Продукт успешно создан',
                content: new OA\JsonContent(
                    ref: new Model(type: StockItemResponse::class)
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации или доменная ошибка',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'successful', type: 'boolean', example: false),
                    new OA\Property(property: 'error', type: 'string', example: 'Имя не может быть пустым')
                ])
            )
        ]
    )]
    public function create(
        #[MapRequestPayload] CreateProductCommand $cmd,
        CommandBusInterface $bus
    ): Response {
        $response = $bus->execute($cmd);

        return new JsonResponse($response, Response::HTTP_CREATED);
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
        Request $request,
        QueryBusInterface $bus,
    ): JsonResponse {
        $query = new GetAllProductsQuery(
            id: (int)$request->query->get('id'),
            page: (int)$request->query->get('page', 1),
            pageSize: (int)$request->query->get('pageSize', 20),
        );

        return new JsonResponse($bus->execute($query));
    }
}
