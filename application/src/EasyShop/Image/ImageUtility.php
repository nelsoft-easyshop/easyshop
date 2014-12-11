<?php

namespace EasyShop\Image;

/**
 * Image Utility Class
 *
 * @author Inon Baguio
 */
class ImageUtility
{

    /**
     * CI_Image_lib Instance
     *
     * @var CI_Image_lib
     */
    private $imageLibrary;  

    /**
     * Constructor. Retrieves Image Libraryss instance
     * 
     */
    public function __construct($imageLibrary)
    {
        $this->imageLibrary = $imageLibrary;
    }

    /**
     * Resizes images
     *
     * @param string $imageDirectory
     * @param string $newDirectory
     * @param array $dimension
     * @param bool $isRatioMainted
     *
     * @return JSONP
     */ 
    public function imageResize($imageDirectory, $newDirectory, $dimension, $isRatioMainted = true)
    {
        
        $config['image_library'] = 'GD2';
        $config['source_image'] = $imageDirectory;
        $config['maintain_ratio'] = $isRatioMainted;
        $config['quality'] = '85%';
        $config['new_image'] = $newDirectory;
        $config['width'] = $dimension[0];
        $config['height'] = $dimension[1]; 

        $this->imageLibrary->clear();        
        $this->imageLibrary->initialize($config); 
        $this->imageLibrary->resize();
    } 
}


