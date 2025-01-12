<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250112145300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE broadcast_image_to_db_entity (id INT AUTO_INCREMENT NOT NULL, broadcast_id INT NOT NULL, image LONGBLOB NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE broadcast_image_to_file_entity (id INT AUTO_INCREMENT NOT NULL, broadcast_id INT NOT NULL, file_path VARCHAR(2048) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE menu_image_entity');
        $this->addSql('ALTER TABLE order_item_entity CHANGE status status ENUM(\'taken\', \'inCooking\',\'pendingPayment\',\'complete\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_image_entity (id INT AUTO_INCREMENT NOT NULL, ь?menu_id INT NOT NULL, image LONGBLOB NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE broadcast_image_to_db_entity');
        $this->addSql('DROP TABLE broadcast_image_to_file_entity');
        $this->addSql('ALTER TABLE order_item_entity CHANGE status status VARCHAR(255) DEFAULT NULL');
    }
}
