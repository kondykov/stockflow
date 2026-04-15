<?php

declare(strict_types=1);

namespace StockFlow\Identity\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Identity\Application\Command\RBAC\AssignRoleCommand;
use StockFlow\Identity\Application\Command\RBAC\BatchUpdateRolesCommand;
use StockFlow\Identity\Application\Command\RBAC\CreateNewRoleCommand;
use StockFlow\Identity\Application\Command\RBAC\UnassignRoleCommand;
use StockFlow\Identity\Application\Command\RBAC\UpdateRoleCommand;
use StockFlow\Identity\Application\Query\RBAC\GetRoleByIdQuery;
use StockFlow\Identity\Application\Query\RBAC\GetRolesQuery;
use StockFlow\Identity\Application\Query\RBAC\GetUserRolesQuery;
use StockFlow\Identity\Domain\Dto\PermissionItemResponse;
use StockFlow\Shared\Identity\Domain\Enum\RBAC\Permission;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted(Permission::IdentityAccess->value)]
    public function newRole(
        #[MapRequestPayload] CreateNewRoleCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($cmd));
    }

    #[Route('/roles/{id}', methods: ['GET'])]
    public function getRoleById(int $id, QueryBusInterface $bus): JsonResponse
    {
        $query = new GetRoleByIdQuery($id);

        return new JsonResponse($bus->execute($query));
    }

    #[Route('/roles/{id}', methods: ['PUT'])]
    public function updateRoleById(
        int $id,
        #[MapRequestPayload] UpdateRoleCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        $cmd = new UpdateRoleCommand(
            id: $id,
            name: $cmd->name,
            permissions: $cmd->permissions
        );

        return new JsonResponse($bus->execute($cmd));
    }


    #[Route('/user-roles', methods: ['GET'])]
    public function getUserRoles(
        #[MapQueryParameter] ?GetUserRolesQuery $query = null,
        QueryBusInterface $bus
    ): JsonResponse {
        $query = $query ?? new GetUserRolesQuery();
        return new JsonResponse($bus->execute($query));
    }

    #[Route('/roles', methods: ['GET'])]
    public function getRoles(
        #[MapQueryParameter] ?GetRolesQuery $query = null,
        QueryBusInterface $bus
    ): JsonResponse {
        $query = $query ?? new GetRolesQuery();
        return new JsonResponse($bus->execute($query));
    }

    #[Route('/role/batch-update', methods: ['POST'])]
    #[IsGranted(Permission::IdentityAccess->value)]
    #[OA\Post(
        description: 'Массовое обновление ролей пользователя. Заменяет все текущие роли на новые',
        summary: 'Обновить роли пользователя',
        requestBody: new OA\RequestBody(
            description: 'Данные для обновления ролей',
            required: true,
            content: new OA\JsonContent(
                required: ['userId', 'roles'],
                properties: [
                    new OA\Property(
                        property: 'userId',
                        description: 'ID пользователя',
                        type: 'integer',
                        example: 42
                    ),
                    new OA\Property(
                        property: 'roles',
                        description: 'Массив имён ролей',
                        type: 'array',
                        items: new OA\Items(
                            type: 'string',
                            example: 'ROLE_MANAGER'
                        ),
                        example: ['ROLE_MANAGER', 'ROLE_OPERATOR']
                    ),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Роли успешно обновлены',
        content: new OA\JsonContent(
            required: ['successful'],
            properties: [
                new OA\Property(property: 'successful', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', nullable: true, example: null),
                new OA\Property(property: 'data', type: 'object', nullable: true, example: null),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка валидации данных',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'successful', type: 'boolean', example: false),
                new OA\Property(property: 'message', type: 'string', example: 'Роль "INVALID_ROLE" не существует'),
            ]
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Доступ запрещён (нет прав IdentityAccess)',
    )]
    #[OA\Response(
        response: 404,
        description: 'Пользователь не найден',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'successful', type: 'boolean', example: false),
                new OA\Property(property: 'message', type: 'string', example: 'Пользователь не существует'),
            ]
        )
    )]
    public function batchUpdate(
        #[MapRequestPayload] BatchUpdateRolesCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($cmd));
    }

    #[Route('/role/assign', methods: ['POST'])]
    #[IsGranted(Permission::IdentityAccess->value)]
    public function assignRole(
        #[MapRequestPayload] AssignRoleCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($cmd));
    }

    #[Route('/role/unassign', methods: ['DELETE'])]
    #[IsGranted(Permission::IdentityAccess->value)]
    public function unassignRole(
        #[MapRequestPayload] UnassignRoleCommand $cmd,
        CommandBusInterface $bus
    ): JsonResponse {
        return new JsonResponse($bus->execute($cmd));
    }
}
