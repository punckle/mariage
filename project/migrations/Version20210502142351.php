<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502142351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guest_plus_one (id INT AUTO_INCREMENT NOT NULL, guest_id INT NOT NULL, plus_one_id INT NOT NULL, UNIQUE INDEX UNIQ_990E66D9A4AA658 (guest_id), UNIQUE INDEX UNIQ_990E66D5950E1E6 (plus_one_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guest_plus_one ADD CONSTRAINT FK_990E66D9A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE guest_plus_one ADD CONSTRAINT FK_990E66D5950E1E6 FOREIGN KEY (plus_one_id) REFERENCES plus_one (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE guest_plus_one');
    }
}
