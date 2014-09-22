<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140826125837 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `es_product` 
                    ADD FULLTEXT INDEX `fk_es_product_es_name1_idx` (`name` ASC);');

        $this->addSql('ALTER TABLE `es_product` 
                    DROP INDEX `fk_es_product_es_keywords1_idx` ,
                    ADD FULLTEXT INDEX `fk_es_product_es_keywords1_idx` (`search_keyword` ASC);');
 



    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `es_product` 
                    DROP INDEX `fk_es_product_es_keywords1_idx` ,
                    ADD FULLTEXT INDEX `fk_es_product_es_keywords1_idx` (`keywords` ASC, `name` ASC),
                    DROP INDEX `fk_es_product_es_name1_idx` ;');
    }
}
