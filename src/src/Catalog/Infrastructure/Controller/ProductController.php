<?php

declare(strict_types=1);

namespace StockFlow\Catalog\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Catalog\Application\Command\CreateProductCommand;
use StockFlow\Catalog\Application\Query\GetAllProductsQuery;
use StockFlow\Catalog\Domain\Dto\ProductResponse;
use StockFlow\Shared\Identity\Domain\Enum\RBAC\Permission;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Catalog — Products')]
#[Route('/api/catalog/product', name: 'catalog_product_')]
final class ProductController extends AbstractController
{
    #[Route('', name: 'create', methods: ['POST'])]
    #[IsGranted(Permission::ProductCreate->value)]
    #[OA\Post(
        summary: 'Создать продукт',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: CreateProductCommand::class))
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Продукт успешно создан',
                content: new OA\JsonContent(ref: new Model(type: ProductResponse::class))
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'successful', type: 'boolean', example: false),
                    new OA\Property(property: 'error', type: 'string')
                ])
            )
        ]
    )]
    public function create(
        #[MapRequestPayload] CreateProductCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($cmd), Response::HTTP_CREATED);
    }

    #[Route('', name: 'get_all', methods: ['GET'])]
    #[OA\Get(
        summary: 'Получить список продуктов',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список продуктов',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: ProductResponse::class))
                )
            )
        ]
    )]
    public function getAll(
        #[MapQueryString] GetAllProductsQuery $query,
        QueryBusInterface $bus,
    ): JsonResponse {
        return new JsonResponse($bus->execute($query));
    }
}
