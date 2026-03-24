<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260323154449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lead (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, phone_number VARCHAR(20) DEFAULT NULL, company_name VARCHAR(255) NOT NULL, jobtitle VARCHAR(150) DEFAULT NULL, source VARCHAR(255) DEFAULT NULL, subject VARCHAR(100) NOT NULL, comment LONGTEXT DEFAULT NULL, converted_at DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, last_modified_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_289161CB989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE opportunity ADD content LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lead');
        $this->addSql('ALTER TABLE opportunity DROP content');
    }
}
