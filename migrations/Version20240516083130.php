<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516083130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circle_user DROP CONSTRAINT fk_dc9cb5370ee2ff6');
        $this->addSql('ALTER TABLE circle_user DROP CONSTRAINT fk_dc9cb53a76ed395');
        $this->addSql('DROP TABLE circle_user');
        $this->addSql('ALTER TABLE "user" ADD phone VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE circle_user (circle_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(circle_id, user_id))');
        $this->addSql('CREATE INDEX idx_dc9cb53a76ed395 ON circle_user (user_id)');
        $this->addSql('CREATE INDEX idx_dc9cb5370ee2ff6 ON circle_user (circle_id)');
        $this->addSql('ALTER TABLE circle_user ADD CONSTRAINT fk_dc9cb5370ee2ff6 FOREIGN KEY (circle_id) REFERENCES circle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE circle_user ADD CONSTRAINT fk_dc9cb53a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP phone');
    }
}
