<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516080912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circle ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE circle ADD CONSTRAINT FK_D4B76579B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D4B76579B03A8386 ON circle (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE circle DROP CONSTRAINT FK_D4B76579B03A8386');
        $this->addSql('DROP INDEX IDX_D4B76579B03A8386');
        $this->addSql('ALTER TABLE circle DROP created_by_id');
    }
}
