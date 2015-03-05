<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150227162326 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `es_member_feature_restrict` (
                `id_member_feature_restrict` int(11) NOT NULL AUTO_INCREMENT,
                `member_id` int(10) unsigned NOT NULL,
                `feature_restrict_id` int(11) NOT NULL,
                `is_delete` int(11) DEFAULT '0',
                PRIMARY KEY (`id_member_feature_restrict`),
                KEY `fk_es_member_feature_restrict_1_idx` (`member_id`),
                KEY `fk_es_member_feature_restrict_2_idx` (`feature_restrict_id`),
                CONSTRAINT `fk_es_member_feature_restrict_1` FOREIGN KEY (`member_id`) REFERENCES `es_member` (`id_member`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                CONSTRAINT `fk_es_member_feature_restrict_2` FOREIGN KEY (`feature_restrict_id`) REFERENCES `es_feature_restrict` (`id_feature_restrict`) ON DELETE NO ACTION ON UPDATE NO ACTION
            )
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE `es_member_feature_restrict`");
    }
}
