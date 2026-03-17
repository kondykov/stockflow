<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260315132322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE wh_warehouse ADD address VARCHAR(255) NOT NULL default ''");
        $this->addSql('ALTER TABLE wh_warehouse ALTER name TYPE VARCHAR(80)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wh_warehouse DROP address');
        $this->addSql('ALTER TABLE wh_warehouse ALTER name TYPE VARCHAR(255)');
    }
}
