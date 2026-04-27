<?php

declare(strict_types=1);

namespace StockFlow\Document\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Document\Application\Query\GetMovementHistoryQuery;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use StockFlow\Shared\Kernel\Domain\ValueObject\PaginatedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Document — History')]
#[Route('/api/warehouse/{id}/stock/movements', name: 'warehouse_stock_history_')]
class MovementHistoryController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    #[Route(name: 'get_history', methods: ['GET'])]
    #[OA\Get(
        summary: 'Получить историю движений по складу',
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID склада', in: 'path', required: true,
                schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'page', description: 'Страница', in: 'query', schema: new OA\Schema(type: 'integer',
                default: 1)),
            new OA\Parameter(name: 'pageSize', description: 'Количество на странице', in: 'query',
                schema: new OA\Schema(type: 'integer', default: 20)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'История перемещений (Paginated)',
                content: new OA\JsonContent(ref: new Model(type: PaginatedResponse::class))
            )
        ]
    )]
    public function __invoke(
        int $id,
        Request $request,
        QueryBusInterface $queryBus
    ): JsonResponse {
        $query = new GetMovementHistoryQuery(
            warehouseId: $id,
            page: (int)$request->query->get('page', 1),
            pageSize: (int)$request->query->get('pageSize', 20)
        );

        $errors = $this->validator->validate($query);
        if (count($errors) > 0) {
            throw new ValidationFailedException($query, $errors);
        }

        return new JsonResponse($queryBus->execute($query));
    }
}
