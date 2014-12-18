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
     * Constructor. Retrieves Upload Library instance
     * 
     */
    public function __construct($uploadLibrary)
    {
        $this->uploadLibrary = $uploadLibrary;
    }
}

