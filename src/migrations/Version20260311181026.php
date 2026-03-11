<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260311181026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rbac_roles ADD discr VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE rbac_roles ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER INDEX uniq_identifier_email RENAME TO UNIQ_1483A5E9E7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rbac_roles DROP discr');
        $this->addSql('ALTER TABLE rbac_roles ALTER name TYPE VARCHAR(50)');
        $this->addSql('ALTER INDEX uniq_1483a5e9e7927c74 RENAME TO uniq_identifier_email');
    }
}
