<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsCatImg
 *
 * @ORM\Table(name="es_cat_img")
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsCatImgRepository")
 */
class EsCatImg
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_cat", type="integer", nullable=true)
     */
    private $idCat;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    private $path = '';



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idCat
     *
     * @param integer $idCat
     * @return EsCatImg
     */
    public function setIdCat($idCat)
    {
        $this->idCat = $idCat;

        return $this;
    }

    /**
     * Get idCat
     *
     * @return integer 
     */
    public function getIdCat()
    {
        return $this->idCat;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return EsCatImg
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
}
