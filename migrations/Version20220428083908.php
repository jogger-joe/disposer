<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220428083908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE housing_tag (housing_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(housing_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_51F334A1AD5873E3 ON housing_tag (housing_id)');
        $this->addSql('CREATE INDEX IDX_51F334A1BAD26311 ON housing_tag (tag_id)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, title VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE housing_tag ADD CONSTRAINT FK_51F334A1AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE housing_tag ADD CONSTRAINT FK_51F334A1BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE housing_tag DROP CONSTRAINT FK_51F334A1BAD26311');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP TABLE housing_tag');
        $this->addSql('DROP TABLE tag');
    }
}
