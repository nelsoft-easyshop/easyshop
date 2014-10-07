<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsOptionalAttrhead
 *
 * @ORM\Table(name="es_optional_attrhead", indexes={@ORM\Index(name="fk_es_optional_attrhead_es_product_idx", columns={"product_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsOptionalAttrheadRepository")
 */
class EsOptionalAttrhead
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_optional_attrhead", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOptionalAttrhead;

    /**
     * @var string
     *
     * @ORM\Column(name="field_name", type="string", length=45, nullable=true)
     */
    private $fieldName = '';

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
     * Get idOptionalAttrhead
     *
     * @return integer 
     */
    public function getIdOptionalAttrhead()
    {
        return $this->idOptionalAttrhead;
    }

    /**
     * Set fieldName
     *
     * @param string $fieldName
     * @return EsOptionalAttrhead
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Get fieldName
     *
     * @return string 
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Set product
     *
     * @param \EasyShop\Entities\EsProduct $product
     * @return EsOptionalAttrhead
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
