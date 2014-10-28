<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\Version;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141024191750 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            UPDATE `es_location_lookup` SET `location`='Penarrubia' WHERE `id_location`='172';
            UPDATE `es_location_lookup` SET `location`='Penablanca' WHERE `id_location`='380';
            UPDATE `es_location_lookup` SET `location`='Santo Nino (Faire)' WHERE `id_location`='387';
            UPDATE `es_location_lookup` SET `location`='Dona Remedios Trinidad' WHERE `id_location`='492';
            UPDATE `es_location_lookup` SET `location`='Science City Of Munoz' WHERE `id_location`='509';
            UPDATE `es_location_lookup` SET `location`='Penaranda' WHERE `id_location`='513';
            UPDATE `es_location_lookup` SET `location`='Dasmarinas City' WHERE `id_location`='618';
            UPDATE `es_location_lookup` SET `location`='Mendez (Mendez-Nunez)' WHERE `id_location`='626';
            UPDATE `es_location_lookup` SET `location`='City Of Binan' WHERE `id_location`='638';
            UPDATE `es_location_lookup` SET `location`='Los Banos' WHERE `id_location`='646';
            UPDATE `es_location_lookup` SET `location`='Sofronio Espanola' WHERE `id_location`='778';
            UPDATE `es_location_lookup` SET `location`='Sofronio Espanola' WHERE `id_location`='779';
            UPDATE `es_location_lookup` SET `location`='Sagnay' WHERE `id_location`='859';
            UPDATE `es_location_lookup` SET `location`='Duenas' WHERE `id_location`='991';
            UPDATE `es_location_lookup` SET `location`='Santo Nino' WHERE `id_location`='1302';
            UPDATE `es_location_lookup` SET `location`='Pinan (New Pinan)' WHERE `id_location`='1339';
            UPDATE `es_location_lookup` SET `location`='Sergio Osmena Sr.' WHERE `id_location`='1344';
            UPDATE `es_location_lookup` SET `location`='Mabini (Dona Alicia)' WHERE `id_location`='1498';
            UPDATE `es_location_lookup` SET `location`='Santo Nino' WHERE `id_location`='1582';
        ");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
