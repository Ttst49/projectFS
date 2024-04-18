<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418075350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profile_profile (profile_source INT NOT NULL, profile_target INT NOT NULL, PRIMARY KEY(profile_source, profile_target))');
        $this->addSql('CREATE INDEX IDX_52E9749337A01814 ON profile_profile (profile_source)');
        $this->addSql('CREATE INDEX IDX_52E974932E45489B ON profile_profile (profile_target)');
        $this->addSql('ALTER TABLE profile_profile ADD CONSTRAINT FK_52E9749337A01814 FOREIGN KEY (profile_source) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile_profile ADD CONSTRAINT FK_52E974932E45489B FOREIGN KEY (profile_target) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE profile_profile DROP CONSTRAINT FK_52E9749337A01814');
        $this->addSql('ALTER TABLE profile_profile DROP CONSTRAINT FK_52E974932E45489B');
        $this->addSql('DROP TABLE profile_profile');
    }
}
