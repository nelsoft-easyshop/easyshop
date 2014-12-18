<?php 


namespace EasyShop\Upload;

/**
 * Image Upload Cass
 *
 * @author Inon Baguio
 */
class Upload
{
    /**
     * CI_Upload Instance
     *
     * @var CI_Upload
     */
    private $uploadLibrary;

    /**
     * CI_Image_lib Instance
     *
     * @var CI_Image_lib
     */
    private $imageLibrary;  

    /**
     *  Entity Manager Instance
     *
     *  @var Doctrine\ORM\EntityManager
     */
    private $em;          

    /**
     * Constructor. Retrieves Upload Library instance
     * 
     */
    public function __construct($uploadLibrary, $imageLibrary, $em)
    {
        $this->uploadLibrary = $uploadLibrary;
        $this->imageLibrary = $imageLibrary; 
        $this->em = $em;               
    }

    public function uploadImage($uid, $data)
    {   
        $memberObj = $this->em->getRepository('EasyShop\Entities\EsMember')
                                        ->find($uid);    
        var_dump($memberObj->getImgUrl());
        // var_dump($uid);
        // var_dump($data);
    }
}

