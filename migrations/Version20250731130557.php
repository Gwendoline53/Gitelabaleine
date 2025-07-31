<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250731130557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, locale VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temoignage (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, note INT NOT NULL, is_approved TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', locale VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE about ADD contenu LONGTEXT NOT NULL, ADD locale VARCHAR(255) NOT NULL, ADD cle VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE home ADD contenu LONGTEXT NOT NULL, ADD locale VARCHAR(255) NOT NULL, ADD `key` VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE temoignage');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE about DROP contenu, DROP locale, DROP cle');
        $this->addSql('ALTER TABLE home DROP contenu, DROP locale, DROP `key`');
    }
}
