<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702070504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE secret ADD hash VARCHAR(255) NOT NULL, ADD secret_text LONGTEXT NOT NULL, ADD created_at DATETIME NOT NULL, ADD expires_at DATETIME DEFAULT NULL, ADD remaining_views INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5CA2E8E5D1B862B8 ON secret (hash)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5CA2E8E5D1B862B8 ON secret');
        $this->addSql('ALTER TABLE secret DROP hash, DROP secret_text, DROP created_at, DROP expires_at, DROP remaining_views');
    }
}
