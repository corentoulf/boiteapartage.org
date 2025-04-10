<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319205001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP CONSTRAINT fk_1f1b251e12469de2');
        $this->addSql('DROP INDEX idx_1f1b251e12469de2');
        $this->addSql('ALTER TABLE item DROP category_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE item ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT fk_1f1b251e12469de2 FOREIGN KEY (category_id) REFERENCES item_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1f1b251e12469de2 ON item (category_id)');
    }
}
