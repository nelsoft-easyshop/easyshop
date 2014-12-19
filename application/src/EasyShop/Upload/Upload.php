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
     *  Allowed Image Types
     *
     *  @var string
     */
    private $allowedImageTypes = 'gif|jpg|png|jpeg';             

    /**
     * Constructor. Retrieves Upload Library instance
     * 
     */
    public function __construct($uploadLibrary)
    {
        $this->uploadLibrary = $uploadLibrary;
    }

    /**
     * Initiatest upload image method
     * @param string $path
     * @param string $filename
     * @param bool $isOverWriteImage
     * @param int $maxSize
     * @param int $maxWidth
     * @param int $maxHeight
     * @return array
     * 
     */
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
        $errors = [];
        $uploadData = [];
        if (!$this->uploadLibrary->do_upload()){
            $errors = $this->uploadLibrary->display_errors();
        }
        else {
            $uploadData = $this->uploadLibrary->data();
        }
        return [
            'errors' => $errors,
            'uploadData' => $uploadData
        ];        
    }
}

