<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230717160609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE credit_cards (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(36) DEFAULT NULL, holder_name VARCHAR(255) NOT NULL, card_number VARCHAR(19) NOT NULL, expiry_date DATE NOT NULL, INDEX IDX_5CADD653A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, reset_password_token VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credit_cards ADD CONSTRAINT FK_5CADD653A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit_cards DROP FOREIGN KEY FK_5CADD653A76ED395');
        $this->addSql('DROP TABLE credit_cards');
        $this->addSql('DROP TABLE users');

    }
}
