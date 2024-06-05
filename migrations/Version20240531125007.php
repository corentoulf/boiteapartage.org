<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531125007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE item_circle_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE item_circle (id INT NOT NULL, item_id INT NOT NULL, circle_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_524E10B6126F525E ON item_circle (item_id)');
        $this->addSql('CREATE INDEX IDX_524E10B670EE2FF6 ON item_circle (circle_id)');
        $this->addSql('ALTER TABLE item_circle ADD CONSTRAINT FK_524E10B6126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_circle ADD CONSTRAINT FK_524E10B670EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE item_circle_id_seq CASCADE');
        $this->addSql('ALTER TABLE item_circle DROP CONSTRAINT FK_524E10B6126F525E');
        $this->addSql('ALTER TABLE item_circle DROP CONSTRAINT FK_524E10B670EE2FF6');
        $this->addSql('DROP TABLE item_circle');
    }
}
