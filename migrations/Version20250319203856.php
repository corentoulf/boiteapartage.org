<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319203856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD property_1 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD property_2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD property_3 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD property_4 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD property_5 VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE item DROP property_1');
        $this->addSql('ALTER TABLE item DROP property_2');
        $this->addSql('ALTER TABLE item DROP property_3');
        $this->addSql('ALTER TABLE item DROP property_4');
        $this->addSql('ALTER TABLE item DROP property_5');
    }
}
