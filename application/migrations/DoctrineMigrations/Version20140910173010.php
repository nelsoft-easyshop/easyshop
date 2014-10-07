<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140910173010 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `es_problem_report`;");
        $this->addSql("
            CREATE TABLE `es_problem_report` (
            `id_problem_report` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `problem_image_path` TEXT NULL,
            `problem_title` VARCHAR(1024) NULL,
            `problem_description` VARCHAR(1024) NOT NULL DEFAULT '',
            PRIMARY KEY (`id_problem_report`));");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DROP TABLE `es_problem_report`;");
        $this->addSql("
            CREATE TABLE `es_problem_report` (
            `id_problem_report` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `problem_image_path` TEXT NULL,
            `problem_title` VARCHAR(1024) NULL,
            `problem_description` VARCHAR(1024) NOT NULL DEFAULT '',
            PRIMARY KEY (`id_problem_report`));");
    }
}
