<?php

declare(strict_types=1);

namespace StockFlow\Catalog\Infrastructure\Console;

use StockFlow\Catalog\Domain\Event\SyncProductEvent;
use StockFlow\Catalog\Domain\Repository\ProductRepositoryInterface;
use StockFlow\Shared\Kernel\Infrastructure\Messenger\EventDispatcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'catalog:sync_products', description: 'Синхронизация продукции с другими модулями из каталога')]
readonly class SyncProductsCommand
{
    public function __construct(
        private EventDispatcher $dispatcher,
        private ProductRepositoryInterface $repository,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $products = $this->repository->findAllPaginated();

        for ($page = 1; $page <= $products->totalPages; $page++) {
            foreach ($this->repository->findAllPaginated(page: $page)->items as $product) {
                $this->dispatcher->dispatch(new SyncProductEvent(
                    sku: $product->sku,
                    aggregateId: $product->id,
                ));

                $io->writeln(sprintf('Syncing product %d: %s', $product->id, $product->name));
            }
        }

        return Command::SUCCESS;
    }
}
