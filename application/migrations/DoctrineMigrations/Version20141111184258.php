<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141111184258 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            INSERT INTO `oauth_clients` (`client_id`, `client_secret`, `redirect_uri`) VALUES ('j0EHlg4idoNwT7GWltd7VHV52', '6DqL0ZtMNxgcSYELUaSLrfaG1A99LkZHYWUKZQtT6li8qUCeti', '');
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DELETE FROM `oauth_clients` WHERE `client_id`='j0EHlg4idoNwT7GWltd7VHV52';
        ");
    }
}

