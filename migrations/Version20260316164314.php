<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316164314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact ADD title VARCHAR(255) DEFAULT NULL, ADD slug VARCHAR(255) NOT NULL, DROP gender, CHANGE email email VARCHAR(180) NOT NULL, CHANGE phone phone_number VARCHAR(20) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638E7927C74 ON contact (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638989D9B62 ON contact (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_4C62E638E7927C74 ON contact');
        $this->addSql('DROP INDEX UNIQ_4C62E638989D9B62 ON contact');
        $this->addSql('ALTER TABLE contact ADD gender VARCHAR(10) DEFAULT NULL, DROP title, DROP slug, CHANGE email email VARCHAR(150) DEFAULT NULL, CHANGE phone_number phone VARCHAR(20) DEFAULT NULL');
    }
}
