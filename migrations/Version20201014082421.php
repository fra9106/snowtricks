<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014082421 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image CHANGE trick_id trick_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick CHANGE update_date update_date DATETIME NULL');
        $this->addSql('ALTER TABLE user CHANGE avatar avatar VARCHAR(15) NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image CHANGE trick_id trick_id INT NULL');
        $this->addSql('ALTER TABLE trick CHANGE update_date update_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE avatar avatar VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}