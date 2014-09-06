<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsDoctrineMigrationVersions
 *
 * @ORM\Table(name="es_doctrine_migration_versions")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsDoctrineMigrationVersionsRepository")
 */
class EsDoctrineMigrationVersions
{
    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $version;



    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }
}
