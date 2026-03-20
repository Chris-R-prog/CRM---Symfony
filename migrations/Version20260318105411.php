<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318105411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY `FK_8389C3D79B6B5FBA`');
        $this->addSql('ALTER TABLE opportunity ADD opportunity_stage_id INT NOT NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D79B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7DBD3180 FOREIGN KEY (opportunity_stage_id) REFERENCES opportunity_stage (id)');
        $this->addSql('CREATE INDEX IDX_8389C3D7DBD3180 ON opportunity (opportunity_stage_id)');
        $this->addSql('ALTER TABLE opportunity_log ADD opportunity_id INT NOT NULL, ADD stage_id INT NOT NULL');
        $this->addSql('ALTER TABLE opportunity_log ADD CONSTRAINT FK_74CE299E9A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opportunity_log ADD CONSTRAINT FK_74CE299E2298D193 FOREIGN KEY (stage_id) REFERENCES opportunity_stage (id)');
        $this->addSql('CREATE INDEX IDX_74CE299E9A34590F ON opportunity_log (opportunity_id)');
        $this->addSql('CREATE INDEX IDX_74CE299E2298D193 ON opportunity_log (stage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D79B6B5FBA');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7DBD3180');
        $this->addSql('DROP INDEX IDX_8389C3D7DBD3180 ON opportunity');
        $this->addSql('ALTER TABLE opportunity DROP opportunity_stage_id');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT `FK_8389C3D79B6B5FBA` FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opportunity_log DROP FOREIGN KEY FK_74CE299E9A34590F');
        $this->addSql('ALTER TABLE opportunity_log DROP FOREIGN KEY FK_74CE299E2298D193');
        $this->addSql('DROP INDEX IDX_74CE299E9A34590F ON opportunity_log');
        $this->addSql('DROP INDEX IDX_74CE299E2298D193 ON opportunity_log');
        $this->addSql('ALTER TABLE opportunity_log DROP opportunity_id, DROP stage_id');
    }
}
