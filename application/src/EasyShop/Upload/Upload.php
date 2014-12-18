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
     *  Upload Errors
     *
     *  @var array
     */
    private $errors;    

    /**
     *  Upload Data
     *
     *  @var array
     */
    private $uploadData;     

    /**
     *  Allowed Image Types
     *
     *  @var string
     */
    private $allowedImageTypes = 'gif|jpg|png|jpeg';             

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

    public function uploadImage($path, 
                                $filename, 
                                $isOverWriteImage = true, 
                                $maxSize = 5000, 
                                $maxWidth = 5000, 
                                $maxHeight = 5000)
    {   
        $config['overwrite'] = $isOverWriteImage;
        $config['file_name'] = $filename;
        $config['upload_path'] = $path; 
        $config['allowed_types'] = $this->allowedImageTypes;
        $config['max_size'] = $maxSize;
        $config['max_width']  = $maxWidth;
        $config['max_height']  = $maxHeight;
        $this->uploadLibrary->initialize($config);  

        if (!$this->uploadLibrary->do_upload()){
            $this->errors = $this->uploadLibrary->display_errors();
        }
        else {
            $this->uploadData = $this->uploadLibrary->data();
        }
        return [
                'errors' => $this->errors,
                'uploadData' => $this->uploadData
            ];        
    }
}

