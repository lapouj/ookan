<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190916132110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity CHANGE streetnum streetnum INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resto DROP FOREIGN KEY relation_id_userpro');
        $this->addSql('DROP INDEX id_userpro ON resto');
        $this->addSql('ALTER TABLE resto CHANGE id_userpro id_userpro INT DEFAULT NULL, CHANGE street_num street_num INT DEFAULT NULL, CHANGE phone phone VARCHAR(20) DEFAULT NULL, CHANGE website website VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD photo VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity CHANGE streetnum streetnum INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resto CHANGE street_num street_num INT DEFAULT NULL, CHANGE phone phone VARCHAR(20) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE website website VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE id_userpro id_userpro INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resto ADD CONSTRAINT relation_id_userpro FOREIGN KEY (id_userpro) REFERENCES user_pro (id)');
        $this->addSql('CREATE INDEX id_userpro ON resto (id_userpro)');
        $this->addSql('ALTER TABLE user DROP photo, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
