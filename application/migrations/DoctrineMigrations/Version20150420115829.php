<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150420115829 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            INSERT INTO `es_holidaydetails` (`type`, `date_d`, `memo`) VALUES
            ('2', '2015-02-19', 'Chinese New Year'),
            ('1', '2015-02-25', 'EDSA Revolution Anniversary'),
            ('1', '2015-04-02', 'Maundy Thursday'),
            ('1', '2015-04-03', 'Good Friday'),
            ('2', '2015-04-04', 'Black Saturday'),
            ('1', '2015-04-09', 'Day of Valor'),
            ('1', '2015-05-01', 'Labor Day'),
            ('1', '2015-06-12', 'Independence Day'),
            ('2', '2015-08-21', 'Ninoy Aquino Day'),
            ('1', '2015-08-31', 'National Heroes Day'),
            ('2', '2015-11-01', 'All Saints Day'),
            ('1', '2015-11-30', 'Bonifacio Day'),
            ('1', '2015-12-24', 'Additional special non-working day'),
            ('1', '2015-12-25', 'Christmas Day'),
            ('1', '2015-12-30', 'Rizal Day'),
            ('2', '2015-12-31', 'Last Day of the Year');
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='2'
            AND `date_d` = '2015-02-19'
            AND `memo` = 'Chinese New Year';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-02-25'
            AND `memo` = 'EDSA Revolution Anniversary';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-04-02'
            AND `memo` = 'Maundy Thursday';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-04-03'
            AND `memo` = 'Good Friday';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='2' 
            AND `date_d` = '2015-04-04'
            AND `memo` = 'Black Saturday';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-04-09'
            AND `memo` = 'Day of Valor';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-05-01'
            AND `memo` = 'Labor Day';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-06-12'
            AND `memo` = 'Independence Day';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='2' 
            AND `date_d` = '2015-08-21'
            AND `memo` = 'Ninoy Aquino Day';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-08-31'
            AND `memo` = 'National Heroes Day';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='2' 
            AND `date_d` = '2015-11-01'
            AND `memo` = 'All Saints Day';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-11-30'
            AND `memo` = 'Bonifacio Day';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-12-24'
            AND `memo` = 'Additional special non-working day';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-12-25'
            AND `memo` = 'Christmas Day';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='1' 
            AND `date_d` = '2015-12-30'
            AND `memo` = 'Rizal Day';
        ");

        $this->addSql("
            DELETE FROM `es_holidaydetails` WHERE 
            `type`='2' 
            AND `date_d` = '2015-12-31'
            AND `memo` = 'Last Day of the Year';
        ");
    }
}
