<?php

namespace EasyShop\BugReporter;

/**
 * BugReporter Class
 *
 * @author LA Roberto
 */
class BugReporter
{

    /**
     * Entity Manager
     * 
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Constructor.
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Constructor.
     *
     * @param mixed $formData Contains value of the form
     */
    public function createReport($formData)
    {
        $newName = sha1($formData['file']->getClientOriginalName().(string)time());
        $formData['file']->move('./assets/images/reports',$newName);

        $problem = new \EasyShop\Entities\EsProblemReport();

        $problem->setProblemTitle($formData['title']);
        $problem->setProblemDescription($formData['description']);
        $problem->setProblemImagePath('./assets/images/reports/'.$newName);
        
        $this->em->persist($problem);
        $this->em->flush();
    }
}