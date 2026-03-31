<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260330142654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, subject VARCHAR(100) NOT NULL, activity_type VARCHAR(255) NOT NULL, priority VARCHAR(255) NOT NULL, direction VARCHAR(255) NOT NULL, scheduled_at DATETIME NOT NULL, due_date DATETIME DEFAULT NULL, completed_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, last_modified_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, lead_id INT DEFAULT NULL, opportunity_id INT DEFAULT NULL, account_id INT DEFAULT NULL, user_id INT NOT NULL, contact_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_AC74095A989D9B62 (slug), INDEX IDX_AC74095A55458D (lead_id), INDEX IDX_AC74095A9A34590F (opportunity_id), INDEX IDX_AC74095A9B6B5FBA (account_id), INDEX IDX_AC74095AA76ED395 (user_id), INDEX IDX_AC74095AE7A1254A (contact_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE lead_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, label VARCHAR(100) NOT NULL, color VARCHAR(15) NOT NULL, position INT NOT NULL, is_final VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A55458D FOREIGN KEY (lead_id) REFERENCES lead (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A9A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE opportunity CHANGE priority priority VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A55458D');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A9A34590F');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A9B6B5FBA');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AA76ED395');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AE7A1254A');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE lead_status');
        $this->addSql('ALTER TABLE opportunity CHANGE priority priority VARCHAR(255) DEFAULT NULL');
    }
}
