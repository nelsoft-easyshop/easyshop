<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsMemberProdcat
 *
 * @ORM\Table(name="es_member_prodcat", indexes={@ORM\Index(name="fk_es_member_prodcat_1_idx", columns={"memcat_id"}), @ORM\Index(name="fk_es_member_prodcat_2_idx", columns={"product_id"})})
 * @ORM\Entity
 */
class EsMemberProdcat
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_memprod", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMemprod;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createddate", type="datetime", nullable=false)
     */
    private $createddate = 'CURRENT_TIMESTAMP';

    /**
     * @var \EasyShop\Entities\EsMemberCat
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMemberCat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="memcat_id", referencedColumnName="id_memcat")
     * })
     */
    private $memcat;

    /**
     * @var \EasyShop\Entities\EsProduct
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id_product")
     * })
     */
    private $product;



    /**
     * Get idMemprod
     *
     * @return integer 
     */
    public function getIdMemprod()
    {
        return $this->idMemprod;
    }

    /**
     * Set createddate
     *
     * @param \DateTime $createddate
     * @return EsMemberProdcat
     */
    public function setCreateddate($createddate)
    {
        $this->createddate = $createddate;

        return $this;
    }

    /**
     * Get createddate
     *
     * @return \DateTime 
     */
    public function getCreateddate()
    {
        return $this->createddate;
    }

    /**
     * Set memcat
     *
     * @param \EasyShop\Entities\EsMemberCat $memcat
     * @return EsMemberProdcat
     */
    public function setMemcat(\EasyShop\Entities\EsMemberCat $memcat = null)
    {
        $this->memcat = $memcat;

        return $this;
    }

    /**
     * Get memcat
     *
     * @return \EasyShop\Entities\EsMemberCat 
     */
    public function getMemcat()
    {
        return $this->memcat;
    }

    /**
     * Set product
     *
     * @param \EasyShop\Entities\EsProduct $product
     * @return EsMemberProdcat
     */
    public function setProduct(\EasyShop\Entities\EsProduct $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \EasyShop\Entities\EsProduct 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
