<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141008183210 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE es_vendor_subscribe 
                            ADD CONSTRAINT `fk_es_vendor_subscribe_memberId`
                            FOREIGN KEY (`member_id`)
                            REFERENCES `es_member` (id_member)");
        $this->addSql("ALTER TABLE es_vendor_subscribe 
                            ADD CONSTRAINT `fk_es_vendor_subscribe_vendorId`
                            FOREIGN KEY (`vendor_id`)
                            REFERENCES `es_member` (id_member)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("ALTER TABLE es_vendor_subscribe DROP FOREIGN KEY `fk_es_vendor_subscribe_memberId`");
        $this->addSql("ALTER TABLE es_vendor_subscribe DROP FOREIGN KEY `fk_es_vendor_subscribe_vendorId`");
    }
}
