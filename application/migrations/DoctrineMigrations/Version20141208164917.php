<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141208164917 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO `es_order_product_status` (`id_order_product_status`, `name`) VALUES ("99", "Reject") ');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM `es_order_product_status` WHERE `id_order_product_status` = "99" ');
    }
}
