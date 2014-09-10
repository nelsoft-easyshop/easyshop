<?php

namespace EasyShop\BugReporter;

use \DateTime;

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
     *
     * @return \EasyShop\Entities
     */
    public function createReport($formData)
    {
        $problem = new \EasyShop\Entities\EsProblemReport();
        $problem->setProblemTitle($formData['title']);
        $problem->setProblemDescription($formData['description']);
        $problem->setDateAdded(date_create(date("Y-m-d H:i:s")));

        if($formData['file'] !== NULL){
            $newName = sha1($formData['file']->getClientOriginalName().(string)time());
            $formData['file']->move('./assets/images/reports',$newName);
            $problem->setProblemImagePath('./assets/images/reports/'.$newName);
        }
        else{
            $problem->setProblemImagePath('');
        }
        
        $this->em->persist($problem);
        $this->em->flush();

        return $problem;
    }
}
