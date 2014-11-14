<?php

namespace Easyshop\Image;

/**
 * Image utility class
 *
 * @author Sam Gavinio <samgavinio@easyshop.ph>
 */
class ImageUtility
{   
    /**
     * Image LIbrary
     * @var system/libraries/CI_Image_lib
     */
    private $imageLibrary;

    public function __construct($imageLibrary)
    {
        $this->imageLibrary = $imageLibrary;
    }

    /**
     * Resize an image. Returns false if the resized image cannot
     * be created.
     *
     * @param string $sourceFilename
     * @param string $sourceDirectory
     * @param string $destinationDirectory
     * @param integer $width
     * @param integer $height
     * @param string $resultFilename
     * @return boolean
     * 
     */
    public function resizeImage($sourceFilename , $sourceDirectory, $destinationDirectory, $width, $height, $resultFilename = NULL)
    {        
        $pathToSourceImage = $sourceDirectory.$sourceFilename;
        if($resultFilename){
            $pathToDestinationImage = $destinationDirectory.$resultFilename;
        }
        else{
            $pathToDestinationImage = $destinationDirectory.$sourceFilename;
        }

        $config['image_library'] = 'GD2';
        $config['source_image'] = $pathToSourceImage;
        $config['maintain_ratio'] = true;
        $config['quality'] = '85%';
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = $pathToDestinationImage;
        
        if(!file_exists($destinationDirectory)) {  
            if(!mkdir($destinationDirectory)) {  
                return false;  
            }   
        }

        $this->imageLibrary->initialize($config); 
        $isSuccessful = $this->imageLibrary->resize();
        $this->imageLibrary->clear();
        return $isSuccessful;
    }

    
    
}



