<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260910000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create payment and refund tables for the payments service';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payment (id CHAR(36) NOT NULL, order_id VARCHAR(36) NOT NULL, user_email VARCHAR(100) NOT NULL, amount INT NOT NULL, currency VARCHAR(3) NOT NULL, payment_method VARCHAR(64) NOT NULL, provider VARCHAR(32) NOT NULL, idempotency_key VARCHAR(120) NOT NULL, status VARCHAR(32) NOT NULL, provider_payment_intent_id VARCHAR(64) DEFAULT NULL, provider_charge_id VARCHAR(64) DEFAULT NULL, failure_code VARCHAR(64) DEFAULT NULL, failure_message VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, UNIQUE INDEX UNIQ_PAYMENT_ORDER_ID (order_id), UNIQUE INDEX UNIQ_PAYMENT_IDEMPOTENCY_KEY (idempotency_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refund (id CHAR(36) NOT NULL, payment_id CHAR(36) NOT NULL, amount INT NOT NULL, status VARCHAR(32) NOT NULL, provider_refund_id VARCHAR(64) DEFAULT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX IDX_5B7B7E6C4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE refund ADD CONSTRAINT FK_5B7B7E6C4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE refund DROP FOREIGN KEY FK_5B7B7E6C4C3A3BB');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE refund');
    }
}
