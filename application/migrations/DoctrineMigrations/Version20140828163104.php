<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140828163104 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE es_order_status SET name = "VOID" WHERE order_status = 2');

    }

    public function down(Schema $schema)
    {
       $this->addSql('UPDATE es_order_status SET name = "DRAGONPAY EXPIRED" WHERE order_status = 2');

    }
}
