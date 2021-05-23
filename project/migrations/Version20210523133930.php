<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210523133930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guest_plus_one (id INT AUTO_INCREMENT NOT NULL, guest_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, apero TINYINT(1) NOT NULL, dinner TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_990E66D9A4AA658 (guest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guest_plus_one ADD CONSTRAINT FK_990E66D9A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE guest_plus_one');
    }
}
