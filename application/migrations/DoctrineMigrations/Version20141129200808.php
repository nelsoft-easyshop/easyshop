<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141129200808 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `es_store_color` (
                `id_store_color` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(45) NOT NULL DEFAULT '',
                `hexadecimal` varchar(45) NOT NULL,
                PRIMARY KEY (`id_store_color`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('Easyshop', 'FF893A')" );
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('California', 'F89406')" );
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('Pomegranate', 'F22613')" );
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('Radical Red', 'F62459')" );
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('Honey Flower', '674172')" );
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('Ming', '336E7B')" );
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('San Marino', '446CB3')" );
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('Jelly Bean', '2574A9')" );
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('Salem', '1E824C')" );
        $this->addSql("INSERT INTO es_store_color (`name`, `hexadecimal`)VALUES('Lynch', '6C7A89')" );
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE `es_store_color`
        ");

    }
}
