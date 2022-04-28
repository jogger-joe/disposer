<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220428112156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD updated TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD created_by VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD updated_by VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD deletedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP created');
        $this->addSql('ALTER TABLE comment DROP updated');
        $this->addSql('ALTER TABLE comment DROP created_by');
        $this->addSql('ALTER TABLE comment DROP updated_by');
        $this->addSql('ALTER TABLE comment DROP deletedAt');
    }
}
