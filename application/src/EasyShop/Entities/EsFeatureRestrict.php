<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsFeatureRestrict
 *
 * @ORM\Table(name="es_feature_restrict")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsFeatureRestrictRepository")
 */
class EsFeatureRestrict
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_feature_restrict", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFeatureRestrict;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_user", type="integer", nullable=false)
     */
    private $maxUser;

    /**
     * @var integer
     */
    const REAL_TIME_CHAT = 1;


    /**
     * Get idFeatureRestrict
     *
     * @return integer 
     */
    public function getIdFeatureRestrict()
    {
        return $this->idFeatureRestrict;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsFeatureRestrict
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set maxUser
     *
     * @param integer $maxUser
     * @return EsFeatureRestrict
     */
    public function setMaxUser($maxUser)
    {
        $this->maxUser = $maxUser;

        return $this;
    }

    /**
     * Get maxUser
     *
     * @return integer 
     */
    public function getMaxUser()
    {
        return $this->maxUser;
    }
}
