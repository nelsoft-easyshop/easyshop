<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * SearchTbl
 *
 * @ORM\Table(name="search_tbl", indexes={@ORM\Index(name="fulltext", columns={"search_keyword"})})
 * @ORM\Entity
 */
class SearchTbl
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
     * @var string
     *
     * @ORM\Column(name="search_keyword", type="text", nullable=true)
     */
    private $searchKeyword;



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
     * Set searchKeyword
     *
     * @param string $searchKeyword
     * @return SearchTbl
     */
    public function setSearchKeyword($searchKeyword)
    {
        $this->searchKeyword = $searchKeyword;

        return $this;
    }

    /**
     * Get searchKeyword
     *
     * @return string 
     */
    public function getSearchKeyword()
    {
        return $this->searchKeyword;
    }
}
