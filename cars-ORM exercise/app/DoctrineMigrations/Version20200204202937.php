<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20200204202937 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parts CHANGE supplier_id supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sales DROP INDEX UNIQ_6B8170449395C3F3, ADD INDEX IDX_6B8170449395C3F3 (customer_id)');
        $this->addSql('ALTER TABLE sales ADD car_id INT DEFAULT NULL, CHANGE customer_id customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B817044C3C6F69F FOREIGN KEY (car_id) REFERENCES cars (id)');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B8170449395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B817044C3C6F69F ON sales (car_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parts CHANGE supplier_id supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sales DROP INDEX IDX_6B8170449395C3F3, ADD UNIQUE INDEX UNIQ_6B8170449395C3F3 (customer_id)');
        $this->addSql('ALTER TABLE sales DROP FOREIGN KEY FK_6B817044C3C6F69F');
        $this->addSql('ALTER TABLE sales DROP FOREIGN KEY FK_6B8170449395C3F3');
        $this->addSql('DROP INDEX UNIQ_6B817044C3C6F69F ON sales');
        $this->addSql('ALTER TABLE sales DROP car_id, CHANGE customer_id customer_id INT DEFAULT NULL');
    }
}
