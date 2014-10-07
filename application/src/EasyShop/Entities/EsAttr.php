<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsAttr
 *
 * @ORM\Table(name="es_attr", indexes={@ORM\Index(name="fk_es_attr_es_attr_lookuplist1_idx", columns={"attr_lookuplist_id"}), @ORM\Index(name="fk_es_attr_es_cat1_idx", columns={"cat_id"}), @ORM\Index(name="fk_es_attr_es_datatype1_idx", columns={"datatype_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsAttrRepository")
 */
class EsAttr
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_attr", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAttr;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name = '';

    /**
     * @var \EasyShop\Entities\EsAttrLookuplist
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsAttrLookuplist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attr_lookuplist_id", referencedColumnName="id_attr_lookuplist")
     * })
     */
    private $attrLookuplist;

    /**
     * @var \EasyShop\Entities\EsCat
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsCat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cat_id", referencedColumnName="id_cat")
     * })
     */
    private $cat;

    /**
     * @var \EasyShop\Entities\EsDatatype
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsDatatype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="datatype_id", referencedColumnName="id_datatype")
     * })
     */
    private $datatype;



    /**
     * Get idAttr
     *
     * @return integer 
     */
    public function getIdAttr()
    {
        return $this->idAttr;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsAttr
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
     * Set attrLookuplist
     *
     * @param \EasyShop\Entities\EsAttrLookuplist $attrLookuplist
     * @return EsAttr
     */
    public function setAttrLookuplist(\EasyShop\Entities\EsAttrLookuplist $attrLookuplist = null)
    {
        $this->attrLookuplist = $attrLookuplist;

        return $this;
    }

    /**
     * Get attrLookuplist
     *
     * @return \EasyShop\Entities\EsAttrLookuplist 
     */
    public function getAttrLookuplist()
    {
        return $this->attrLookuplist;
    }

    /**
     * Set cat
     *
     * @param \EasyShop\Entities\EsCat $cat
     * @return EsAttr
     */
    public function setCat(\EasyShop\Entities\EsCat $cat = null)
    {
        $this->cat = $cat;

        return $this;
    }

    /**
     * Get cat
     *
     * @return \EasyShop\Entities\EsCat 
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Set datatype
     *
     * @param \EasyShop\Entities\EsDatatype $datatype
     * @return EsAttr
     */
    public function setDatatype(\EasyShop\Entities\EsDatatype $datatype = null)
    {
        $this->datatype = $datatype;

        return $this;
    }

    /**
     * Get datatype
     *
     * @return \EasyShop\Entities\EsDatatype 
     */
    public function getDatatype()
    {
        return $this->datatype;
    }
}
