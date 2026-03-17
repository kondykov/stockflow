<?php

declare(strict_types=1);

namespace StockFlow\Shared\Kernel\Infrastructure\EventListener;

use Assert\InvalidArgumentException as AssertException;
use Assert\LazyAssertionException;
use StockFlow\Shared\Kernel\Infrastructure\Response\ApiResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Serializer\Exception\UnsupportedFormatException;
use Symfony\Component\TypeInfo\Exception\UnsupportedException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class ApiResponseListener
{
    public function __construct(
        #[Autowire('%kernel.environment%')]
        private string $env,
        private bool $useWhoops
    ) {
    }

    #[AsEventListener(event: KernelEvents::RESPONSE, priority: -10)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();
        if ($event->getRequest()->getRequestFormat() !== 'json' &&
            !str_contains($response->headers->get('Content-Type', ''), 'application/json')) {
            return;
        }

        $content = json_decode($response->getContent(), true);

        if (isset($content['successful'])) {
            return;
        }

        $statusCode = $response->getStatusCode();
        $isSuccessful = $statusCode >= 200 && $statusCode < 300;

        $wrappedData = $isSuccessful
            ? ApiResponse::success($content)
            : ApiResponse::error($content['detail'] ?? $content['message'] ?? 'Error', $content['violations'] ?? null);

        $event->setResponse(new JsonResponse($wrappedData, $statusCode));
    }

    #[AsEventListener(event: KernelEvents::EXCEPTION, priority: 100)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (($exception instanceof HandlerFailedException || $exception instanceof UnprocessableEntityHttpException) && $exception->getPrevious()) {
            $exception = $exception->getPrevious();
        }

        $isValidation = $exception instanceof ValidationFailedException;
        $isLazyAssertion = $exception instanceof LazyAssertionException;
        $isAssert = $exception instanceof AssertException;

        if ((!$isValidation && !$isLazyAssertion && !$isAssert) && $this->env === 'dev' && $this->useWhoops) {
            return;
        }

        $message = 'Внутренняя ошибка сервера';
        $data = null;
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($isValidation) {
            $code = 422;
            $message = 'Ошибка валидации данных';
            foreach ($exception->getViolations() as $violation) {
                $data[$violation->getPropertyPath()] = $violation->getMessage();
            }
        } elseif ($isLazyAssertion) {
            $code = 422;
            $message = 'Ошибка валидации данных';
            $data = [];
            foreach ($exception->getErrorExceptions() as $error) {
                $propertyPath = $error->getPropertyPath() ?: 'validation';
                $data[$propertyPath] = $error->getMessage();
            }
        } elseif ($isAssert) {
            $code = 422;
            $message = $exception->getMessage();
            $data = [$exception->getPropertyPath() => $message];
        } elseif ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
            $message = $exception->getMessage();

            $previous = $exception->getPrevious();
            if ($previous instanceof ValidationFailedException) {
                $code = 422;
                $message = 'Ошибка валидации данных';
                foreach ($previous->getViolations() as $violation) {
                    $data[$violation->getPropertyPath()] = $violation->getMessage();
                }
            }
        }

        $event->setResponse(new JsonResponse(ApiResponse::error($message, $data), $code));
    }
}
