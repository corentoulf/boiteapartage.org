<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516083726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_circle_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_circle (id INT NOT NULL, user_id_id INT NOT NULL, circle_id INT NOT NULL, date_created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B1E57E449D86650F ON user_circle (user_id_id)');
        $this->addSql('CREATE INDEX IDX_B1E57E4470EE2FF6 ON user_circle (circle_id)');
        $this->addSql('ALTER TABLE user_circle ADD CONSTRAINT FK_B1E57E449D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_circle ADD CONSTRAINT FK_B1E57E4470EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_circle_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_circle DROP CONSTRAINT FK_B1E57E449D86650F');
        $this->addSql('ALTER TABLE user_circle DROP CONSTRAINT FK_B1E57E4470EE2FF6');
        $this->addSql('DROP TABLE user_circle');
    }
}
