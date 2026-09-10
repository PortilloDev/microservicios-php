<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260910000002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create esim table for the esim provisioning service';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE esim (id CHAR(36) NOT NULL, order_id VARCHAR(36) NOT NULL, user_email VARCHAR(100) NOT NULL, provider VARCHAR(32) NOT NULL, status VARCHAR(32) NOT NULL, payment_id VARCHAR(36) DEFAULT NULL, iccid VARCHAR(22) DEFAULT NULL, imsi VARCHAR(20) DEFAULT NULL, msisdn VARCHAR(20) DEFAULT NULL, smdp_address VARCHAR(120) DEFAULT NULL, matching_id VARCHAR(64) DEFAULT NULL, activation_code VARCHAR(255) DEFAULT NULL, qr_code_url VARCHAR(500) DEFAULT NULL, profile_name VARCHAR(120) DEFAULT NULL, data_allowance VARCHAR(32) DEFAULT NULL, validity VARCHAR(32) DEFAULT NULL, failure_message VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, UNIQUE INDEX UNIQ_ESIM_ORDER_ID (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE esim');
    }
}
