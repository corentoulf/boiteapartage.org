<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260702201126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan DROP start_date');
        $this->addSql('ALTER TABLE loan DROP end_date');
        $this->addSql('ALTER TABLE loan ALTER requested_start_date DROP NOT NULL');
        $this->addSql('ALTER TABLE loan ALTER requested_end_date DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE loan ADD start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE loan ADD end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE loan ALTER requested_start_date SET NOT NULL');
        $this->addSql('ALTER TABLE loan ALTER requested_end_date SET NOT NULL');
    }
}
