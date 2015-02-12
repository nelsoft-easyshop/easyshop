<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsSocialMediaProvider
 *
 * @ORM\Table(name="es_social_media_provider")
 * @ORM\Entity
 */
class EsSocialMediaProvider
{

    const FACEBOOK = 1;

    const GOOGLE = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_social_media_provider", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSocialMediaProvider;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;



    /**
     * Get idSocialMediaProvider
     *
     * @return integer
     */
    public function getIdSocialMediaProvider()
    {
        return $this->idSocialMediaProvider;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsSocialMediaProvider
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
}
