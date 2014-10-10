<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141010162145 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        
        // ES_PRODUCT
        // Remove index
        $this->addSql("ALTER TABLE `es_product` 
            DROP INDEX `fk_es_product_es_keywords1_idx` ,
            DROP INDEX `fk_es_product_es_name1_idx` ;
        ");

        // Add index
        $this->addSql("ALTER TABLE `es_product` 
            ADD FULLTEXT INDEX `ft_es_product_name_idx` (`name` ASC);
        ");

        // Add index
        $this->addSql("ALTER TABLE `es_product` 
            ADD DROP FULLTEXT INDEX `ft_es_product_search_keyword_idx` (`search_keyword` ASC);
        ");

        // ES_CAT
        // Remove index
        $this->addSql("ALTER TABLE `es_cat` 
            DROP INDEX `ft_es_cat` ;
        ");

        // Add index
        $this->addSql("ALTER TABLE `es_cat` 
            ADD FULLTEXT INDEX `ft_es_cat_name_idx` (`name` ASC);
        ");

        // ES_BRAND
        // Remove index
        $this->addSql("ALTER TABLE `es_brand` 
            DROP INDEX `name_fulltext` ;
        ");

        // Add index
        $this->addSql("ALTER TABLE `es_brand` 
            ADD FULLTEXT INDEX `ft_es_brand_name` (`name` ASC);
        ");

        // ES_KEYWORDS
        // Remove index
        $this->addSql("ALTER TABLE `es_keywords` 
            DROP INDEX `fulltext` ;
        ");

        // Add index
        $this->addSql("ALTER TABLE `es_keywords` 
            ADD FULLTEXT INDEX `ft_es_keywords_idx` (`keywords` ASC);
        ");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
