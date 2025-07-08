<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250708073318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE item_save_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE item_save (id INT NOT NULL, owner_id INT NOT NULL, item_type_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, property_1 VARCHAR(255) DEFAULT NULL, property_2 VARCHAR(255) DEFAULT NULL, property_3 VARCHAR(255) DEFAULT NULL, property_4 VARCHAR(255) DEFAULT NULL, property_5 VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_mime_type INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9D567E257E3C61F9 ON item_save (owner_id)');
        $this->addSql('CREATE INDEX IDX_9D567E25CE11AAC7 ON item_save (item_type_id)');
        $this->addSql('COMMENT ON COLUMN item_save.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN item_save.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE item_save ADD CONSTRAINT FK_9D567E257E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_save ADD CONSTRAINT FK_9D567E25CE11AAC7 FOREIGN KEY (item_type_id) REFERENCES item_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item DROP image_name');
        $this->addSql('ALTER TABLE item DROP image_size');
        $this->addSql('ALTER TABLE item DROP updated_at');
        $this->addSql('ALTER TABLE item DROP image_mime_type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE item_save_id_seq CASCADE');
        $this->addSql('ALTER TABLE item_save DROP CONSTRAINT FK_9D567E257E3C61F9');
        $this->addSql('ALTER TABLE item_save DROP CONSTRAINT FK_9D567E25CE11AAC7');
        $this->addSql('DROP TABLE item_save');
        $this->addSql('ALTER TABLE item ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD image_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD image_mime_type INT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN item.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }
}
