<?php

declare(strict_types=1);

namespace SubwayRoutesApi\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class CreateManufacturerTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE manafacturer (id UUID NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, state VARCHAR(100) DEFAULT NULL, postal_code VARCHAR(20) DEFAULT NULL, website VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN roles.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN users.id IS \'\'');
        $this->addSql('COMMENT ON COLUMN users.role_id IS \'\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE manafacturer');
        $this->addSql('COMMENT ON COLUMN roles.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.role_id IS \'(DC2Type:uuid)\'');
    }
}
