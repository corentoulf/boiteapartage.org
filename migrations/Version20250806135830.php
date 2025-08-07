<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250806135830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE loan_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE loan (id INT NOT NULL, lender_id INT NOT NULL, borrower_id INT NOT NULL, requested_start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, requested_end_date DATE NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5D30D03855D3E3D ON loan (lender_id)');
        $this->addSql('CREATE INDEX IDX_C5D30D0311CE312B ON loan (borrower_id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03855D3E3D FOREIGN KEY (lender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0311CE312B FOREIGN KEY (borrower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE loan_id_seq CASCADE');
        $this->addSql('ALTER TABLE loan DROP CONSTRAINT FK_C5D30D03855D3E3D');
        $this->addSql('ALTER TABLE loan DROP CONSTRAINT FK_C5D30D0311CE312B');
        $this->addSql('DROP TABLE loan');
    }
}
