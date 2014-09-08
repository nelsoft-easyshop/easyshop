<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsProblemReport
 *
 * @ORM\Table(name="es_problem_report")
 * @ORM\Entity
 */
class EsProblemReport
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_problem_report", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProblemReport;

    /**
     * @var string
     *
     * @ORM\Column(name="problem_image_path", type="text", nullable=true)
     */
    private $problemImagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="problem_title", type="string", length=1024, nullable=false)
     */
    private $problemTitle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="problem_description", type="string", length=1024, nullable=false)
     */
    private $problemDescription = '';



    /**
     * Get idProblemReport
     *
     * @return integer 
     */
    public function getIdProblemReport()
    {
        return $this->idProblemReport;
    }

    /**
     * Set problemImagePath
     *
     * @param string $problemImagePath
     * @return EsProblemReport
     */
    public function setProblemImagePath($problemImagePath)
    {
        $this->problemImagePath = $problemImagePath;

        return $this;
    }

    /**
     * Get problemImagePath
     *
     * @return string 
     */
    public function getProblemImagePath()
    {
        return $this->problemImagePath;
    }

    /**
     * Set problemTitle
     *
     * @param string $problemTitle
     * @return EsProblemReport
     */
    public function setProblemTitle($problemTitle)
    {
        $this->problemTitle = $problemTitle;

        return $this;
    }

    /**
     * Get problemTitle
     *
     * @return string 
     */
    public function getProblemTitle()
    {
        return $this->problemTitle;
    }

    /**
     * Set problemDescription
     *
     * @param string $problemDescription
     * @return EsProblemReport
     */
    public function setProblemDescription($problemDescription)
    {
        $this->problemDescription = $problemDescription;

        return $this;
    }

    /**
     * Get problemDescription
     *
     * @return string 
     */
    public function getProblemDescription()
    {
        return $this->problemDescription;
    }
}
