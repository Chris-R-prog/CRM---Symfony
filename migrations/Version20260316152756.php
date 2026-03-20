<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316152756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, gender VARCHAR(10) DEFAULT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, jobtitle VARCHAR(150) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, mobile VARCHAR(20) DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, addressline1 VARCHAR(255) DEFAULT NULL, addressline2 VARCHAR(255) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, postalcode VARCHAR(20) DEFAULT NULL, country VARCHAR(100) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, last_modified_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contact');
    }
}
