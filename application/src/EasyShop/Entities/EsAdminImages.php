<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsAdminImages
 *
 * @ORM\Table(name="es_admin_images")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsAdminImagesRepository")
 */
class EsAdminImages
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_admin_image", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAdminImage;

    /**
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=100, nullable=true)
     */
    private $imageName;



    /**
     * Get idAdminImage
     *
     * @return integer 
     */
    public function getIdAdminImage()
    {
        return $this->idAdminImage;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return EsAdminImages
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string 
     */
    public function getImageName()
    {
        return $this->imageName;
    }
}
