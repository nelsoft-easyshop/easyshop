<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsQueue
 *
 * @ORM\Table(name="es_queue", indexes={@ORM\Index(name="fk_es_queue_type_idx", columns={"type"}), @ORM\Index(name="fk_es_queue_status_idx", columns={"status"})})
 * @ORM\Entity
 */
class EsQueue
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_queue", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idQueue;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", nullable=false)
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    private $dateCreated = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_executed", type="datetime", nullable=true)
     */
    private $dateExecuted;

    /**
     * @var \EasyShop\Entities\EsQueueStatus
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsQueueStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="id_status")
     * })
     */
    private $status;

    /**
     * @var \EasyShop\Entities\EsQueueType
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsQueueType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id_type")
     * })
     */
    private $type;



    /**
     * Get idQueue
     *
     * @return integer 
     */
    public function getIdQueue()
    {
        return $this->idQueue;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return EsQueue
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return EsQueue
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateExecuted
     *
     * @param \DateTime $dateExecuted
     * @return EsQueue
     */
    public function setDateExecuted($dateExecuted)
    {
        $this->dateExecuted = $dateExecuted;

        return $this;
    }

    /**
     * Get dateExecuted
     *
     * @return \DateTime 
     */
    public function getDateExecuted()
    {
        return $this->dateExecuted;
    }

    /**
     * Set status
     *
     * @param \EasyShop\Entities\EsQueueStatus $status
     * @return EsQueue
     */
    public function setStatus(\EasyShop\Entities\EsQueueStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \EasyShop\Entities\EsQueueStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param \EasyShop\Entities\EsQueueType $type
     * @return EsQueue
     */
    public function setType(\EasyShop\Entities\EsQueueType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \EasyShop\Entities\EsQueueType 
     */
    public function getType()
    {
        return $this->type;
    }
}
