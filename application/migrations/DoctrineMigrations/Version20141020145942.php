<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141020145942 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE `es_payment_method_user` (
                `id_payment_method_user` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `member_id` INT(10) UNSIGNED NULL,
                `payment_method_id` INT(11) NULL,
            PRIMARY KEY (`id_payment_method_user`),
            INDEX `fk_es_payment_method_user_member_id` (`member_id` ASC),
            INDEX `fk_es_payment_method_user_payment_method_id` (`payment_method_id` ASC),
            CONSTRAINT `fk_es_payment_method_user_member_idx`
                FOREIGN KEY (`member_id`)
                REFERENCES `es_member` (`id_member`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
            CONSTRAINT `fk_es_payment_method_user_payment_idx`
                FOREIGN KEY (`payment_method_id`)
                REFERENCES `es_payment_method` (`id_payment_method`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP TABLE `es_payment_method_user`;
        ");
    }
}
