<?php

declare(strict_types=1);

namespace StockFlow\Identity\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Identity\Application\Command\ChangePasswordCommand;
use StockFlow\Identity\Application\Command\CreateUserCommand;
use StockFlow\Identity\Application\Command\DeleteUserCommand;
use StockFlow\Identity\Application\Command\UpdateUserCommand;
use StockFlow\Identity\Application\Query\GetCurrentUserDataQuery;
use StockFlow\Identity\Application\Query\GetUserQuery;
use StockFlow\Identity\Application\Query\GetUsersQuery;
use StockFlow\Identity\Domain\Dto\UserResponse;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Authentication')]
#[Route('/api/identity', name: 'api_identity_')]
class AuthenticationController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    #[OA\Post(summary: 'Регистрация')]
    #[OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'successful', type: 'boolean', example: true),
        new OA\Property(property: 'data', type: 'integer')
    ]))]
    public function register(
        #[MapRequestPayload] CreateUserCommand $cmd,
        CommandBusInterface $bus
    ): mixed {
        return new JsonResponse($bus->execute($cmd), Response::HTTP_CREATED);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    #[OA\Post(summary: 'JWT Login', requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
        new OA\Property(property: 'email', type: 'string'),
        new OA\Property(property: 'password', type: 'string')
    ])))]
    public function authenticate(): void
    {
    }

    #[Route('/user-data', name: 'user', methods: ['GET'])]
    #[OA\Get(summary: 'Профиль')]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'successful', type: 'boolean', example: true),
        new OA\Property(property: 'data', ref: new Model(type: UserResponse::class))
    ]))]
    public function getCurrentUserData(
        #[MapQueryString] GetCurrentUserDataQuery $query,
        QueryBusInterface $bus
    ): mixed {
        return new JsonResponse($bus->execute($query));
    }

    #[Route('/change-password', name: 'change-password', methods: ['POST'])]
    #[OA\Post(summary: 'Смена пароля')]
    public function changePassword(
        #[MapRequestPayload] ChangePasswordCommand $cmd,
        CommandBusInterface $bus
    ): mixed {
        return new JsonResponse($bus->execute($cmd));
    }
}
