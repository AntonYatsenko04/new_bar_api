<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031145345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cook_entity CHANGE name token VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE manager_entity CHANGE name token VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_item_entity CHANGE status status ENUM(\'taken\', \'inCooking\',\'pendingPayment\',\'complete\')');
        $this->addSql('ALTER TABLE waiter_entity CHANGE name token VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE waiter_entity CHANGE token name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cook_entity CHANGE token name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE manager_entity CHANGE token name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_item_entity CHANGE status status VARCHAR(255) DEFAULT NULL');
    }
}
