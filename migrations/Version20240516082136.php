<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516082136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE circle_user (circle_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(circle_id, user_id))');
        $this->addSql('CREATE INDEX IDX_DC9CB5370EE2FF6 ON circle_user (circle_id)');
        $this->addSql('CREATE INDEX IDX_DC9CB53A76ED395 ON circle_user (user_id)');
        $this->addSql('ALTER TABLE circle_user ADD CONSTRAINT FK_DC9CB5370EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE circle_user ADD CONSTRAINT FK_DC9CB53A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE circle ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE circle_user DROP CONSTRAINT FK_DC9CB5370EE2FF6');
        $this->addSql('ALTER TABLE circle_user DROP CONSTRAINT FK_DC9CB53A76ED395');
        $this->addSql('DROP TABLE circle_user');
        $this->addSql('ALTER TABLE circle DROP created_at');
    }
}
