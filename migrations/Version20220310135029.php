<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310135029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE furniture ADD created_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
        $this->addSql('ALTER TABLE furniture ADD updated_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
        $this->addSql('ALTER TABLE housing ADD created_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
        $this->addSql('ALTER TABLE housing ADD updated_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
        $this->addSql('ALTER TABLE service ADD created_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
        $this->addSql('ALTER TABLE service ADD updated_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
        $this->addSql('ALTER TABLE supporter ADD created_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
        $this->addSql('ALTER TABLE supporter ADD updated_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD created_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD updated_by VARCHAR(255) DEFAULT \'unknown\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE service DROP created_by');
        $this->addSql('ALTER TABLE service DROP updated_by');
        $this->addSql('ALTER TABLE supporter DROP created_by');
        $this->addSql('ALTER TABLE supporter DROP updated_by');
        $this->addSql('ALTER TABLE furniture DROP created_by');
        $this->addSql('ALTER TABLE furniture DROP updated_by');
        $this->addSql('ALTER TABLE housing DROP created_by');
        $this->addSql('ALTER TABLE housing DROP updated_by');
        $this->addSql('ALTER TABLE "user" DROP created_by');
        $this->addSql('ALTER TABLE "user" DROP updated_by');
    }
}
