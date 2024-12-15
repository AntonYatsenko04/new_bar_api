<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241027131423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cook_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manager_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_entity (id INT AUTO_INCREMENT NOT NULL, table_entity_id INT NOT NULL, waiter_id INT NOT NULL, INDEX IDX_CDA754BD76612A7 (table_entity_id), INDEX IDX_CDA754BDE9F3D07E (waiter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item_entity (id INT AUTO_INCREMENT NOT NULL, cook_id INT NOT NULL, order_entity_id INT NOT NULL, status ENUM(\'taken\', \'inCooking\',\'pendingPayment\',\'complete\'), INDEX IDX_93634E21B0D5B835 (cook_id), INDEX IDX_93634E213DA206A5 (order_entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_menu_item_entity (id INT AUTO_INCREMENT NOT NULL, menu_item_id INT NOT NULL, order_item_id INT DEFAULT NULL, count INT NOT NULL, INDEX IDX_823B3BF79AB44FE0 (menu_item_id), INDEX IDX_823B3BF7E415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE table_entity (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE waiter_entity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BD76612A7 FOREIGN KEY (table_entity_id) REFERENCES table_entity (id)');
        $this->addSql('ALTER TABLE order_entity ADD CONSTRAINT FK_CDA754BDE9F3D07E FOREIGN KEY (waiter_id) REFERENCES waiter_entity (id)');
        $this->addSql('ALTER TABLE order_item_entity ADD CONSTRAINT FK_93634E21B0D5B835 FOREIGN KEY (cook_id) REFERENCES cook_entity (id)');
        $this->addSql('ALTER TABLE order_item_entity ADD CONSTRAINT FK_93634E213DA206A5 FOREIGN KEY (order_entity_id) REFERENCES order_entity (id)');
        $this->addSql('ALTER TABLE order_menu_item_entity ADD CONSTRAINT FK_823B3BF79AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_entity (id)');
        $this->addSql('ALTER TABLE order_menu_item_entity ADD CONSTRAINT FK_823B3BF7E415FB15 FOREIGN KEY (order_item_id) REFERENCES order_item_entity (id)');
        $this->addSql('ALTER TABLE broadcast_entity CHANGE description description VARCHAR(1000) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entity DROP FOREIGN KEY FK_CDA754BD76612A7');
        $this->addSql('ALTER TABLE order_entity DROP FOREIGN KEY FK_CDA754BDE9F3D07E');
        $this->addSql('ALTER TABLE order_item_entity DROP FOREIGN KEY FK_93634E21B0D5B835');
        $this->addSql('ALTER TABLE order_item_entity DROP FOREIGN KEY FK_93634E213DA206A5');
        $this->addSql('ALTER TABLE order_menu_item_entity DROP FOREIGN KEY FK_823B3BF79AB44FE0');
        $this->addSql('ALTER TABLE order_menu_item_entity DROP FOREIGN KEY FK_823B3BF7E415FB15');
        $this->addSql('DROP TABLE cook_entity');
        $this->addSql('DROP TABLE manager_entity');
        $this->addSql('DROP TABLE menu_entity');
        $this->addSql('DROP TABLE order_entity');
        $this->addSql('DROP TABLE order_item_entity');
        $this->addSql('DROP TABLE order_menu_item_entity');
        $this->addSql('DROP TABLE table_entity');
        $this->addSql('DROP TABLE waiter_entity');
        $this->addSql('ALTER TABLE broadcast_entity CHANGE description description VARCHAR(1000) DEFAULT NULL');
    }
}
