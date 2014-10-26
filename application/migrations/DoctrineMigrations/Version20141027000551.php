<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141027000551 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE es_brand SET image = 'apple.png' WHERE name='Apple' ");
        $this->addSql("UPDATE es_brand SET image = 'lg.png' WHERE name='Lg' ");
        $this->addSql("UPDATE es_brand SET image = 'bsmobile.png' WHERE name='BS Mobile' ");
        $this->addSql("UPDATE es_brand SET image = 'ckk.png' WHERE name='CKK' ");
        $this->addSql("INSERT INTO es_brand (`name`, `description`, `image`, `sort_order`,  `url`, `is_main`) VALUES ('G-melody', 'G-melody', 'gmelody.png', '0','', '0')   ");
        $this->addSql("UPDATE es_brand SET image = 'huawei.png' WHERE name='Huawei' ");
        $this->addSql("INSERT INTO es_brand (`name`, `description`, `image`, `sort_order`,  `url`, `is_main`) VALUES ('Luvable-friends', 'Luvable Friends', 'lovablefriends.png', '0','', '0')   ");
        $this->addSql("INSERT INTO es_brand (`name`, `description`, `image`, `sort_order`,  `url`, `is_main`) VALUES ('Michaela', 'Michaela', 'michaela.png', '0','', '0')   ");
        $this->addSql("UPDATE es_brand SET image = 'nokia.png' WHERE name='Nokia' ");
        $this->addSql("UPDATE es_brand SET image = 'samsung.png' WHERE name='Samsung' ");
        $this->addSql("UPDATE es_brand SET image = 'skkmobile.png' WHERE name='SKK Mobile' ");
        $this->addSql("INSERT INTO es_brand (`name`, `description`, `image`, `sort_order`,  `url`, `is_main`) VALUES ('ZH&K Mobile', 'ZH&K Mobile', 'zh&kmobile.png', '0','', '0')   ");
   }

    public function down(Schema $schema)
    {
        $this->addSql("UPDATE es_brand SET image = '' WHERE name='Apple' ");
        $this->addSql("UPDATE es_brand SET image = '' WHERE name='Lg' ");
        $this->addSql("UPDATE es_brand SET image = '' WHERE name='BS Mobile' ");
        $this->addSql("UPDATE es_brand SET image = '' WHERE name='CKK' ");
        $this->addSql("DELETE FROM es_brand where name = 'G-melody' ");
        $this->addSql("UPDATE es_brand SET image = '' WHERE name='Huawei' ");
        $this->addSql("DELETE FROM es_brand where name = 'Luvable-friends' ");
        $this->addSql("DELETE FROM es_brand where name = 'Michaela' ");
        $this->addSql("UPDATE es_brand SET image = '' WHERE name='Nokia' ");
        $this->addSql("UPDATE es_brand SET image = '' WHERE name='Samsung' ");
        $this->addSql("UPDATE es_brand SET image = '' WHERE name='SKK Mobile' ");
        $this->addSql("UPDATE es_brand SET image = '' WHERE name='ZH&K Mobile' ");
    }
}
