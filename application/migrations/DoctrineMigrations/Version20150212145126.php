<?php


namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150212145126 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            INSERT INTO `es_ban_type` (`id_ban_type`, `title`, `message`) VALUES
            ('2', 'Excessive Refunds', 'This account has been temporarily suspended due to multiple refunded trasactions. For more details and clarifications contact us at info@easyshop.ph.');
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
           DELETE FROM es_ban_type WHERE id_ban_type = 2;
        ");
    }
}


