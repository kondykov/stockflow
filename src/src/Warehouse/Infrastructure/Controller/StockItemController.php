<?php

declare(strict_types=1);

namespace StockFlow\Warehouse\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Warehouse\Application\Command\AddNewStockItemCommand;
use StockFlow\Warehouse\Domain\ValueObject\StockItemResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Warehouse — Products')]
#[Route('/api/warehouse/stock/item', name: 'warehouse_product_')]
class StockItemController extends AbstractController
{
    /**
     * Deprecated
     *
     * Синхронизация происходит автоматически
     *
     * @param AddNewStockItemCommand $cmd
     * @param CommandBusInterface $bus
     * @return Response
     */
//    #[Route(name: 'create', methods: ['POST'])]
//    #[OA\Post(
//        summary: 'Создать продукт',
//        requestBody: new OA\RequestBody(
//            required: true,
//            content: new OA\JsonContent(
//                ref: new Model(type: AddNewStockItemCommand::class)
//            )
//        ),
//        responses: [
//            new OA\Response(
//                response: 201,
//                description: 'Продукт успешно создан',
//                content: new OA\JsonContent(
//                    ref: new Model(type: StockItemResponse::class)
//                )
//            ),
//            new OA\Response(
//                response: 400,
//                description: 'Ошибка валидации или доменная ошибка',
//                content: new OA\JsonContent(properties: [
//                    new OA\Property(property: 'successful', type: 'boolean', example: false),
//                    new OA\Property(property: 'error', type: 'string', example: 'Имя не может быть пустым')
//                ])
//            )
//        ]
//    )]
    public function create(
        #[MapRequestPayload] AddNewStockItemCommand $cmd,
        CommandBusInterface $bus
    ): Response {
        $response = $bus->execute($cmd);

        return new JsonResponse($response, Response::HTTP_CREATED);
    }
}
