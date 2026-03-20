<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316125235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY `FK_7D3656A4F5B7AF75`');
        $this->addSql('DROP INDEX IDX_7D3656A4F5B7AF75 ON account');
        $this->addSql('ALTER TABLE account ADD addressline1 VARCHAR(255) DEFAULT NULL, ADD addressline2 VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(100) DEFAULT NULL, ADD postalcode VARCHAR(20) DEFAULT NULL, ADD country VARCHAR(100) DEFAULT NULL, DROP address_id');
        $this->addSql('DROP TABLE address');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account ADD address_id INT DEFAULT NULL, DROP addressline1, DROP addressline2, DROP city, DROP postalcode, DROP country');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT `FK_7D3656A4F5B7AF75` FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_7D3656A4F5B7AF75 ON account (address_id)');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, addressline1 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, addressline2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, city VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, postalcode VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, country VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, created_at DATETIME NOT NULL, last_modified_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }
}
