<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250729071603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE verification_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE verification_request (id INT NOT NULL, user_id_id INT NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, destination_email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_20FDDF4E9D86650F ON verification_request (user_id_id)');
        $this->addSql('COMMENT ON COLUMN verification_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE verification_request ADD CONSTRAINT FK_20FDDF4E9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE verification_request_id_seq CASCADE');
        $this->addSql('ALTER TABLE verification_request DROP CONSTRAINT FK_20FDDF4E9D86650F');
        $this->addSql('DROP TABLE verification_request');
    }
}
