<?php

declare(strict_types=1);

namespace StockFlow\Catalog\Infrastructure\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use StockFlow\Catalog\Application\Command\CreateProductCommand;
use StockFlow\Catalog\Application\Command\UpdateProductCommand;
use StockFlow\Catalog\Application\Query\GetAllProductsQuery;
use StockFlow\Catalog\Application\Query\GetProductByIdQuery;
use StockFlow\Catalog\Domain\Dto\ProductResponse;
use StockFlow\Shared\Identity\Domain\Enum\RBAC\Permission;
use StockFlow\Shared\Kernel\Application\Command\CommandBusInterface;
use StockFlow\Shared\Kernel\Application\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['metadata'],
                    properties: [
                        new OA\Property(
                            property: 'metadata',
                            type: 'string',
                            example: '{"name":"Товар","skuCode":"SKU123","skuName":"SKU Name","attributes":[]}'
                        ),
                        new OA\Property(
                            property: 'imageMetadata',
                            type: 'string',
                            example: '[{"index":0,"isCover":true}]'
                        ),
                        new OA\Property(
                            property: 'files[]',
                            type: 'string',
                            format: 'binary'
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Продукт успешно создан',
                content: new OA\JsonContent(ref: new Model(type: ProductResponse::class))
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации'
            )
        ]
    )]
    public function create(
        Request $request,
        CommandBusInterface $bus,
        ValidatorInterface $validator
    ): JsonResponse {
        $rawMetadata = $request->request->get('metadata');
        $metadata = is_string($rawMetadata) ? json_decode($rawMetadata, true) : $rawMetadata ?? [];

        $imageMetadata = [];
        $rawImageMetadata = $request->request->get('imageMetadata');
        if ($rawImageMetadata) {
            $imageMetadata = is_string($rawImageMetadata) ? json_decode($rawImageMetadata, true) : $rawImageMetadata;
        }

        $images = [];
        $files = $request->files->all();

        if (!empty($imageMetadata) && !empty($files['files'])) {
            foreach ($imageMetadata as $meta) {
                $idx = $meta['index'] ?? null;
                $file = $files['files'][$idx] ?? null;

                if ($file && $file instanceof UploadedFile) {
                    $images[] = [
                        'file' => $file,
                        'isCover' => (bool)($meta['isCover'] ?? false)
                    ];
                }
            }
        }

        $cmd = new CreateProductCommand(
            name: (string)($metadata['name'] ?? ''),
            skuCode: (string)($metadata['skuCode'] ?? ''),
            skuName: (string)($metadata['skuName'] ?? ''),
            attributes: is_array($metadata['attributes'] ?? []) ? $metadata['attributes'] : [],
            images: $images,
            coverImageIndex: 0
        );

        $errors = $validator->validate($cmd);
        if (count($errors) > 0) {
            throw new ValidationFailedException($cmd, $errors);
        }

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

    #[Route('/{id}', name: 'get_product', methods: ['GET'])]
    public function getById(int $id, QueryBusInterface $bus): JsonResponse
    {
        $query = new GetProductByIdQuery(id: $id);

        return new JsonResponse($bus->execute($query));
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[IsGranted(Permission::ProductEdit->value)]
    #[OA\Put(
        summary: 'Обновить продукт',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['metadata'],
                    properties: [
                        new OA\Property(
                            property: 'metadata',
                            type: 'string',
                            example: '{"name":"Товар","skuCode":"SKU123","skuName":"SKU Name","attributes":[]}'
                        ),
                        new OA\Property(
                            property: 'newImagesMetadata',
                            type: 'string',
                            example: '[{"isCover":true}]'
                        ),
                        new OA\Property(
                            property: 'existingImages',
                            type: 'string',
                            example: '[{"id":1,"isCover":false}]'
                        ),
                        new OA\Property(
                            property: 'deletedImageIds',
                            type: 'string',
                            example: '[2,3]'
                        ),
                        new OA\Property(
                            property: 'newImages[]',
                            type: 'string',
                            format: 'binary'
                        ),
                    ],
                    type: 'object'
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Продукт успешно обновлён',
                content: new OA\JsonContent(ref: new Model(type: ProductResponse::class))
            ),
            new OA\Response(
                response: 404,
                description: 'Продукт не найден'
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации'
            )
        ]
    )]
    public function update(
        int $id,
        Request $request,
        ValidatorInterface $validator,
        CommandBusInterface $bus
    ): JsonResponse {
        $rawMetadata = $request->request->get('metadata');
        $metadata = is_string($rawMetadata) ? json_decode($rawMetadata, true) : $rawMetadata ?? [];

        $rawAttributes = $metadata['attributes'] ?? [];
        $attributes = is_array($rawAttributes) ? $rawAttributes : [];

        $rawExistingImages = $request->request->get('existingImages');
        $existingImages = is_string($rawExistingImages) ? json_decode($rawExistingImages, true) : $rawExistingImages ?? [];

        $rawDeletedIds = $request->request->get('deletedImageIds');
        $deletedImageIds = is_string($rawDeletedIds) ? json_decode($rawDeletedIds, true) : $rawDeletedIds ?? [];

        $newImages = $request->files->all()['newImages'] ?? [];

        $cmd = new UpdateProductCommand(
            id: $id,
            name: (string)($metadata['name'] ?? ''),
            skuCode: (string)($metadata['skuCode'] ?? ''),
            skuName: (string)($metadata['skuName'] ?? ''),
            attributes: is_array($attributes) ? $attributes : [],
            newImages: $newImages,
            existingImages: is_array($existingImages) ? $existingImages : [],
            deletedImageIds: is_array($deletedImageIds) ? $deletedImageIds : [],
            coverIndex: (int)$metadata['coverIndex']
        );

        $errors = $validator->validate($cmd);
        if (count($errors) > 0) {
            throw new ValidationFailedException($cmd, $errors);
        }

        return new JsonResponse($bus->execute($cmd));
    }
}
