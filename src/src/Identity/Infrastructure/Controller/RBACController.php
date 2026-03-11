<?php

declare(strict_types=1);

namespace StockFlow\Identity\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Identity\Application\Command\RBAC\AssignRoleCommand;
use StockFlow\Identity\Application\Command\RBAC\CreateNewRoleCommand;
use StockFlow\Identity\Application\Command\RBAC\UnassignRoleCommand;
use StockFlow\Identity\Domain\Dto\PermissionItemResponse;
use StockFlow\Identity\Domain\ValueObject\RBAC\Permission;
use StockFlow\Shared\Application\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'RBAC')]
#[Route('api/identity/rbac', name: 'api_identity_')]
class RBACController extends AbstractController
{
    #[Route('/permissions', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Список всех доступных пермишенов системы',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'successful', type: 'boolean', example: true),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: PermissionItemResponse::class))
                )
            ]
        )
    )]
    public function listAll(): Response
    {
        return new JsonResponse(array_map(static fn(Permission $p) => [
            'name' => $p->value,
            'label' => $p->label(),
        ], Permission::cases()));
    }

    #[Route('/role', methods: ['POST'])]
    public function newRole(
        #[MapRequestPayload] CreateNewRoleCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($cmd));
    }

    #[Route('/role/assign', methods: ['POST'])]
    public function assignRole(
        #[MapRequestPayload] AssignRoleCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($cmd));
    }

    #[Route('/role/unassign', methods: ['DELETE'])]
    public function unassignRole(
        #[MapRequestPayload] UnassignRoleCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($cmd));
    }
}
