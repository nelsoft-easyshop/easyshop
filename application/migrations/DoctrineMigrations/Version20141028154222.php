<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141028154222 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE `es_member_merge` (
                      `id_member_merge` INT NOT NULL AUTO_INCREMENT,
                      `member_id` INT UNSIGNED NOT NULL,
                      `social_media_provider_id` INT NOT NULL,
                      `social_media_id` VARCHAR(255) NOT NULL,
                      `created_at` DATETIME NULL,
                      PRIMARY KEY (`id_member_merge`),
                      INDEX `fk_es_member_merge_2_idx` (`social_media_provider_id` ASC),
                      INDEX `idx_id_member` (`member_id` ASC),
                      CONSTRAINT `fk_es_member_es_social_media_provider`
                        FOREIGN KEY (`social_media_provider_id`)
                        REFERENCES `es_social_media_provider` (`id_socialmedia_provider`)
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION)
                        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `es_member_merge`");
    }
}
