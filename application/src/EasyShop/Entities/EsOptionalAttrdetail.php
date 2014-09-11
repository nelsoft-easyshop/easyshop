<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOptionalAttrdetail
 *
 * @ORM\Table(name="es_optional_attrdetail", indexes={@ORM\Index(name="fk_es_optional_attrdetail_idx", columns={"head_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOptionalAttrdetailRepository")
 */
class EsOptionalAttrdetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_optional_attrdetail", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOptionalAttrdetail;

    /**
     * @var string
     *
     * @ORM\Column(name="value_name", type="string", length=45, nullable=true)
     */
    private $valueName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="value_price", type="string", length=45, nullable=true)
     */
    private $valuePrice = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="product_img_id", type="integer", nullable=false)
     */
    private $productImgId = '0';

    /**
     * @var \EasyShop\Entities\EsOptionalAttrhead
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsOptionalAttrhead")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="head_id", referencedColumnName="id_optional_attrhead")
     * })
     */
    private $head;



    /**
     * Get idOptionalAttrdetail
     *
     * @return integer 
     */
    public function getIdOptionalAttrdetail()
    {
        return $this->idOptionalAttrdetail;
    }

    /**
     * Set valueName
     *
     * @param string $valueName
     * @return EsOptionalAttrdetail
     */
    public function setValueName($valueName)
    {
        $this->valueName = $valueName;

        return $this;
    }

    /**
     * Get valueName
     *
     * @return string 
     */
    public function getValueName()
    {
        return $this->valueName;
    }

    /**
     * Set valuePrice
     *
     * @param string $valuePrice
     * @return EsOptionalAttrdetail
     */
    public function setValuePrice($valuePrice)
    {
        $this->valuePrice = $valuePrice;

        return $this;
    }

    /**
     * Get valuePrice
     *
     * @return string 
     */
    public function getValuePrice()
    {
        return $this->valuePrice;
    }

    /**
     * Set productImgId
     *
     * @param integer $productImgId
     * @return EsOptionalAttrdetail
     */
    public function setProductImgId($productImgId)
    {
        $this->productImgId = $productImgId;

        return $this;
    }

    /**
     * Get productImgId
     *
     * @return integer 
     */
    public function getProductImgId()
    {
        return $this->productImgId;
    }

    /**
     * Set head
     *
     * @param \EasyShop\Entities\EsOptionalAttrhead $head
     * @return EsOptionalAttrdetail
     */
    public function setHead(\EasyShop\Entities\EsOptionalAttrhead $head = null)
    {
        $this->head = $head;

        return $this;
    }

    /**
     * Get head
     *
     * @return \EasyShop\Entities\EsOptionalAttrhead 
     */
    public function getHead()
    {
        return $this->head;
    }
}
