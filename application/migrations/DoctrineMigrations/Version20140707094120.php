<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140707094120 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `es_cat` (`id_cat`, `name`, `description`, `keywords`, `parent_id`, `sort_order`, `is_main`, `design1`, `design2`, `design3`, `slug`) VALUES ('1110', 'Special Promo', 'Special Promotional Items', 'Special Promotional', '1', '0', '0', '', '', '', 'promo-special');");
        $this->addSql("UPDATE es_cat SET name = 'Easydeals', description = 'Daily Promotional Items', keywords = 'Daily Promo', slug = 'promo-daily' WHERE id_cat = 1000");
        
    }

    public function down(Schema $schema)
    {
	$this->addSql("DELETE FROM `es_cat` WHERE id_cat = 1110");
        $this->addSql("UPDATE es_cat SET name = 'promo', description = 'promo', keywords = 'promo', slug = 'promo' WHERE id_cat = 1000");

    }
}
