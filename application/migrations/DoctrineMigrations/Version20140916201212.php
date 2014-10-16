<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140916201212 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE `oauth_access_tokens` (
                `access_token` varchar(40) NOT NULL,
                `client_id` varchar(80) NOT NULL,
                `user_id` varchar(255) DEFAULT NULL,
                `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `scope` varchar(2000) DEFAULT NULL,
                PRIMARY KEY (`access_token`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
        );
        
        $this->addSql("CREATE TABLE `oauth_authorization_codes` (
            `authorization_code` varchar(40) NOT NULL,
            `client_id` varchar(80) NOT NULL,
            `user_id` varchar(255) DEFAULT NULL,
            `redirect_uri` varchar(2000) DEFAULT NULL,
            `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `scope` varchar(2000) DEFAULT NULL,
            PRIMARY KEY (`authorization_code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
        );
        
        $this->addSql("CREATE TABLE `oauth_clients` (
            `client_id` varchar(80) NOT NULL,
            `client_secret` varchar(80) NOT NULL,
            `redirect_uri` varchar(2000) NOT NULL,
            `grant_types` varchar(80) DEFAULT NULL,
            `scope` varchar(100) DEFAULT NULL,
            `user_id` varchar(80) DEFAULT NULL,
            PRIMARY KEY (`client_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
        );

        $this->addSql("CREATE TABLE `oauth_jwt` (
            `client_id` varchar(80) NOT NULL,
            `subject` varchar(80) DEFAULT NULL,
            `public_key` varchar(2000) DEFAULT NULL,
            PRIMARY KEY (`client_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        $this->addSql("CREATE TABLE `oauth_refresh_tokens` (
            `refresh_token` varchar(40) NOT NULL,
            `client_id` varchar(80) NOT NULL,
            `user_id` varchar(255) DEFAULT NULL,
            `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `scope` varchar(2000) DEFAULT NULL,
            PRIMARY KEY (`refresh_token`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        $this->addSql("CREATE TABLE `oauth_scopes` (
            `scope` text,
            `is_default` tinyint(1) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        $this->addSql("
            CREATE TABLE `oauth_users` (
            `username` varchar(255) NOT NULL,
            `password` varchar(2000) DEFAULT NULL,
            `first_name` varchar(255) DEFAULT NULL,
            `last_name` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`username`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE `oauth_access_tokens`');
        $this->addSql('DROP TABLE `oauth_authorization_codes`');
        $this->addSql('DROP TABLE `oauth_clients`');
        $this->addSql('DROP TABLE `oauth_jwt`');
        $this->addSql('DROP TABLE `oauth_refresh_tokens`');
        $this->addSql('DROP TABLE `oauth_scopes`');
        $this->addSql('DROP TABLE `oauth_users`');
    }
}
