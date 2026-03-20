<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318095204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(75) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE opportunity (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) DEFAULT NULL, priority VARCHAR(255) DEFAULT NULL, expected_close_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, last_modified_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, account_id INT NOT NULL, UNIQUE INDEX UNIQ_8389C3D7989D9B62 (slug), INDEX IDX_8389C3D79B6B5FBA (account_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE opportunity_contact (opportunity_id INT NOT NULL, contact_id INT NOT NULL, contact_role_id INT NOT NULL, INDEX IDX_6FE72049A34590F (opportunity_id), INDEX IDX_6FE7204E7A1254A (contact_id), INDEX IDX_6FE72044C2C032D (contact_role_id), PRIMARY KEY (opportunity_id, contact_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE opportunity_log (id INT AUTO_INCREMENT NOT NULL, changed_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE opportunity_stage (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(75) NOT NULL, position INT NOT NULL, probability INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D79B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opportunity_contact ADD CONSTRAINT FK_6FE72049A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opportunity_contact ADD CONSTRAINT FK_6FE7204E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opportunity_contact ADD CONSTRAINT FK_6FE72044C2C032D FOREIGN KEY (contact_role_id) REFERENCES contact_role (id)');
        $this->addSql('ALTER TABLE account CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact CHANGE slug slug VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D79B6B5FBA');
        $this->addSql('ALTER TABLE opportunity_contact DROP FOREIGN KEY FK_6FE72049A34590F');
        $this->addSql('ALTER TABLE opportunity_contact DROP FOREIGN KEY FK_6FE7204E7A1254A');
        $this->addSql('ALTER TABLE opportunity_contact DROP FOREIGN KEY FK_6FE72044C2C032D');
        $this->addSql('DROP TABLE contact_role');
        $this->addSql('DROP TABLE opportunity');
        $this->addSql('DROP TABLE opportunity_contact');
        $this->addSql('DROP TABLE opportunity_log');
        $this->addSql('DROP TABLE opportunity_stage');
        $this->addSql('ALTER TABLE account CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE slug slug VARCHAR(255) DEFAULT NULL');
    }
}
