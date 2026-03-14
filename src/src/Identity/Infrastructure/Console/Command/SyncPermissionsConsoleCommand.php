<?php

declare(strict_types=1);

namespace StockFlow\Identity\Infrastructure\Console\Command;

use Doctrine\ORM\EntityManagerInterface;
use StockFlow\Identity\Domain\Entity\RBAC\PermissionEntity;
use StockFlow\Shared\Identity\Domain\Enum\RBAC\Permission;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'identity:rbac:sync',
    description: 'Синхронизация разрешений'
)]
readonly class SyncPermissionsConsoleCommand
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $repository = $this->em->getRepository(PermissionEntity::class);
        $enumCases = Permission::cases();
        $addedCount = 0;

        foreach ($enumCases as $case) {
            $exists = $repository->findOneBy(['name' => $case]);

            if (!$exists) {
                $permission = new PermissionEntity($case);
                $this->em->persist($permission);
                $addedCount++;
                $io->writeln(sprintf(' [+] Добавлено: <info>%s</info>', $case->value));
            }
        }

        if ($addedCount > 0) {
            $this->em->flush();
            $io->success(sprintf('Синхронизация завершена. Добавлено: %d', $addedCount));
        } else {
            $io->info('Все разрешения уже актуальны.');
        }

        return Command::SUCCESS;
    }
}
