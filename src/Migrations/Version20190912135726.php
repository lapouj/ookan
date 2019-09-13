<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190912135726 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resto CHANGE street_num street_num INT DEFAULT NULL, CHANGE phone phone VARCHAR(20) DEFAULT NULL, CHANGE website website VARCHAR(255) DEFAULT NULL, CHANGE id_userpro id_userpro INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sortie ADD date DATETIME NOT NULL, ADD contact VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resto CHANGE street_num street_num INT DEFAULT NULL, CHANGE phone phone VARCHAR(20) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE website website VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE id_userpro id_userpro INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sortie DROP date, DROP contact, DROP type');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}