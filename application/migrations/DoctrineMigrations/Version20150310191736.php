<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150310191736 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            INSERT INTO `es_point_type`
                (`id`,
                `name`,
                `point`)
            VALUES
                ('1', 'REGISTER', '200'),
                ('2', 'LOGIN', '5'),
                ('3', 'SHARE PRODUCT', '5'),
                ('4', 'PURCHASE', '2'),
                ('5', 'TRANSACTION FEEDBACK', '20'),
                ('6', 'REVERT', '0');
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DELETE FROM `es_point_type` WHERE `id`='6';
            DELETE FROM `es_point_type` WHERE `id`='5';
            DELETE FROM `es_point_type` WHERE `id`='4';
            DELETE FROM `es_point_type` WHERE `id`='3';
            DELETE FROM `es_point_type` WHERE `id`='2';
            DELETE FROM `es_point_type` WHERE `id`='1';
        ");
    }
}

