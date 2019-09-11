<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190911080833 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resto ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user DROP photo');
        $this->addSql('ALTER TABLE user_pro ADD lastname VARCHAR(255) NOT NULL, ADD siren VARCHAR(255) NOT NULL, DROP name, DROP siret');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE resto DROP type');
        $this->addSql('ALTER TABLE user ADD photo VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user_pro ADD name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD siret VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP lastname, DROP siren');
    }
}
