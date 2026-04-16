<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260416092500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 1: Add remote_id column to stock_item table, add indices, and improve schema consistency';
    }

    public function up(Schema $schema): void
    {
        // Add remote_id column to wh_stock_items table
        $this->addSql('ALTER TABLE wh_stock_items ADD remote_id VARCHAR(255) DEFAULT NULL');

        // Add indices for frequently queried fields
        $this->addSql('ALTER TABLE wh_stocks ADD INDEX idx_warehouse_product (warehouse_id, product_id)');
        $this->addSql('ALTER TABLE wh_stocks ADD INDEX idx_warehouse (warehouse_id)');
        $this->addSql('ALTER TABLE wh_stock_items ADD INDEX idx_sku (sku_code)');
    }

    public function down(Schema $schema): void
    {
        // Remove indices
        $this->addSql('ALTER TABLE wh_stocks DROP INDEX idx_warehouse_product');
        $this->addSql('ALTER TABLE wh_stocks DROP INDEX idx_warehouse');
        $this->addSql('ALTER TABLE wh_stock_items DROP INDEX idx_sku');

        // Remove remote_id column
        $this->addSql('ALTER TABLE wh_stock_items DROP COLUMN remote_id');
    }
}

