<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsSearchTopic
 *
 * @ORM\Table(name="es_search_topic", indexes={@ORM\Index(name="fk_es_topic_table_1_idx", columns={"category"})})
 * @ORM\Entity(repositoryClass="EasyShop\Repositories\EsSearchTopicRepository")
 */
class EsSearchTopic
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_search_topic", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSearchTopic;

    /**
     * @var string
     *
     * @ORM\Column(name="topic", type="string", length=45, nullable=true)
     */
    private $topic;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="decimal", precision=10, scale=4, nullable=true)
     */
    private $weight = '0.0000';

    /**
     * @var \EasyShop\Entities\EsCat
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsCat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="id_cat")
     * })
     */
    private $category;



    /**
     * Get idSearchTopic
     *
     * @return integer 
     */
    public function getIdSearchTopic()
    {
        return $this->idSearchTopic;
    }

    /**
     * Set topic
     *
     * @param string $topic
     * @return EsSearchTopic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return string 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set weight
     *
     * @param string $weight
     * @return EsSearchTopic
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set category
     *
     * @param \EasyShop\Entities\EsCat $category
     * @return EsSearchTopic
     */
    public function setCategory(\EasyShop\Entities\EsCat $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \EasyShop\Entities\EsCat 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
