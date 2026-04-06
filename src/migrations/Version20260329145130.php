<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260329145130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalog_products ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE wh_stock_items ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE wh_warehouses ADD user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalog_products DROP name');
        $this->addSql('ALTER TABLE wh_stock_items DROP quantity');
        $this->addSql('ALTER TABLE wh_warehouses DROP user_id');
    }
}
