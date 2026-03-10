<?php

declare(strict_types=1);

namespace StockFlow\Identity\Infrastructure\Controller;

use StockFlow\Identity\Application\Command\ChangePasswordCommand;
use StockFlow\Identity\Application\Command\CreateUserCommand;
use StockFlow\Identity\Application\Query\GetCurrentUserDataQuery;
use StockFlow\Shared\Application\Command\CommandBusInterface;
use StockFlow\Shared\Application\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AuthenticationController extends AbstractController
{
    #[Route('api/identity/register', name: 'register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload(acceptFormat: 'json')] CreateUserCommand $command,
        CommandBusInterface $commandBus
    ): Response {
        $response = $commandBus->execute($command);

        return new JsonResponse($response, Response::HTTP_CREATED);
    }

    #[Route('api/identity/login', name: 'login', methods: ['POST'])]
    public function authenticate()
    {
    }

    #[Route('api/identity/user-data', name: 'user', methods: ['GET'])]
    public function getCurrentUserData(
        #[MapRequestPayload(acceptFormat: 'json')] GetCurrentUserDataQuery $query,
        QueryBusInterface $queryBus
    ): Response {
        $response = $queryBus->execute($query);

        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route('api/identity/change-password', name: 'change-password', methods: ['POST'])]
    public function changePassword(
        #[MapRequestPayload(acceptFormat: 'json')] ChangePasswordCommand $command,
        CommandBusInterface $commandBus
    ): Response {
        $response = $commandBus->execute($command);

        return new JsonResponse($response, Response::HTTP_CREATED);
    }
}
