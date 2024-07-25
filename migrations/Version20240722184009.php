<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722184009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circle ADD insee_code VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE circle ADD lat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE circle ADD lng VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE circle DROP insee_code');
        $this->addSql('ALTER TABLE circle DROP lat');
        $this->addSql('ALTER TABLE circle DROP lng');
    }
}
