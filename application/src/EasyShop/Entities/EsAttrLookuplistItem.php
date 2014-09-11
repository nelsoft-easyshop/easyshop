<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsAttrLookuplistItem
 *
 * @ORM\Table(name="es_attr_lookuplist_item", indexes={@ORM\Index(name="fk_es_attr_lookuplist_item_es_attr_lookuplist1_idx", columns={"attr_lookuplist_id"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsAttrLookuplistItemRepository")
 */
class EsAttrLookuplistItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_attr_lookuplist_item", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAttrLookuplistItem;

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
     * Get idAttrLookuplistItem
     *
     * @return integer 
     */
    public function getIdAttrLookuplistItem()
    {
        return $this->idAttrLookuplistItem;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return EsAttrLookuplistItem
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
     * @return EsAttrLookuplistItem
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
}
