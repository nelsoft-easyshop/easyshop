<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141215213105 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            UPDATE `es_activity_type` SET `activity_phrase`='update_information' WHERE `id_activity_type`='1';
            UPDATE `es_activity_type` SET `activity_phrase`='update_product' WHERE `id_activity_type`='2';
            UPDATE `es_activity_type` SET `activity_phrase`='update_transaction' WHERE `id_activity_type`='3';
        ");
    }
}
