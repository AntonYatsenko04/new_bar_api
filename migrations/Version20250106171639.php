<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106171639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE criteria_weights (id INT AUTO_INCREMENT NOT NULL, item_quantity INT NOT NULL, order_quantity INT NOT NULL, price_percentage INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE image_db');
        $this->addSql('ALTER TABLE order_item_entity DROP FOREIGN KEY FK_93634E213DA206A5');
        $this->addSql('ALTER TABLE order_item_entity CHANGE status status ENUM(\'taken\', \'inCooking\',\'pendingPayment\',\'complete\'), CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE order_item_entity ADD CONSTRAINT FK_93634E213DA206A5 FOREIGN KEY (order_entity_id) REFERENCES order_entity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_db (id INT AUTO_INCREMENT NOT NULL, file_data BLOB DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE criteria_weights');
        $this->addSql('ALTER TABLE order_item_entity DROP FOREIGN KEY FK_93634E213DA206A5');
        $this->addSql('ALTER TABLE order_item_entity CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE order_item_entity ADD CONSTRAINT FK_93634E213DA206A5 FOREIGN KEY (order_entity_id) REFERENCES order_entity (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
