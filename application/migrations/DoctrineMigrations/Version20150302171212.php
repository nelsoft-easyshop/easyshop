<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150302171212 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $message = 'This account has been suspended for violating our Terms and Conditions: \"Submitting a false e-mail address, or pretending to be another person is prohibited in EasyShop.ph\".';
        $this->addSql('
            INSERT INTO es_ban_type (`id_ban_type`, `title`, `message`) VALUES (3, "Impersonation", "'.$message.'");
        ');
    }

    public function down(Schema $schema)
    {
          $this->addSql('
            DELETE FROM es_ban_type WHERE id_ban_type = 3;
        ');
    }
}
