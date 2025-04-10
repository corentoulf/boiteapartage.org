<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319072656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE item_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE item_type (id INT NOT NULL, property_1_label VARCHAR(255) DEFAULT NULL, property_2_label VARCHAR(255) DEFAULT NULL, property_3_label VARCHAR(255) DEFAULT NULL, property_4_label VARCHAR(255) DEFAULT NULL, property_5_label VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE item ADD item_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ECE11AAC7 FOREIGN KEY (item_type_id) REFERENCES item_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1F1B251ECE11AAC7 ON item (item_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251ECE11AAC7');
        $this->addSql('DROP SEQUENCE item_type_id_seq CASCADE');
        $this->addSql('DROP TABLE item_type');
        $this->addSql('DROP INDEX IDX_1F1B251ECE11AAC7');
        $this->addSql('ALTER TABLE item DROP item_type_id');
    }
}
