<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260608201923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_favorite_item DROP CONSTRAINT fk_1f9391719d86650f');
        $this->addSql('DROP INDEX idx_1f9391719d86650f');
        $this->addSql('DROP INDEX UNIQ_ITEM_PER_USER');
        $this->addSql('ALTER TABLE user_favorite_item RENAME COLUMN user_id_id TO user_id');
        $this->addSql('ALTER TABLE user_favorite_item ADD CONSTRAINT FK_1F939171A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1F939171A76ED395 ON user_favorite_item (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ITEM_PER_USER ON user_favorite_item (user_id, item_id_id)');
        $this->addSql('ALTER TABLE verification_request DROP CONSTRAINT fk_20fddf4e9d86650f');
        $this->addSql('DROP INDEX idx_20fddf4e9d86650f');
        $this->addSql('ALTER TABLE verification_request RENAME COLUMN user_id_id TO user_id');
        $this->addSql('ALTER TABLE verification_request ADD CONSTRAINT FK_20FDDF4EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_20FDDF4EA76ED395 ON verification_request (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE verification_request DROP CONSTRAINT FK_20FDDF4EA76ED395');
        $this->addSql('DROP INDEX IDX_20FDDF4EA76ED395');
        $this->addSql('ALTER TABLE verification_request RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE verification_request ADD CONSTRAINT fk_20fddf4e9d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_20fddf4e9d86650f ON verification_request (user_id_id)');
        $this->addSql('ALTER TABLE user_favorite_item DROP CONSTRAINT FK_1F939171A76ED395');
        $this->addSql('DROP INDEX IDX_1F939171A76ED395');
        $this->addSql('DROP INDEX uniq_item_per_user');
        $this->addSql('ALTER TABLE user_favorite_item RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE user_favorite_item ADD CONSTRAINT fk_1f9391719d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1f9391719d86650f ON user_favorite_item (user_id_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_item_per_user ON user_favorite_item (user_id_id, item_id_id)');
    }
}
