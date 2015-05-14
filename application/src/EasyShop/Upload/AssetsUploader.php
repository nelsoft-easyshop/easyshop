<?php

namespace Easyshop\Upload;

use \DateTime;

/**
 * Easyshop Upload Class
 *
 * @author Sam Gavinio <samgavinio@easyshop.ph>
 */
class AssetsUploader
{

    const MAX_IMAGE_HEIGHT = 1024;
    
    const MAX_IMAGE_WIDTH = 768;
    
    const MAX_ALLOWABLE_DIMENSION_PX = 5000;
    
    const MAX_ALLOWABLE_SIZE_KB = 5000; 
    
    const ALLOWABLE_IMAGE_MIME_TYPES = "gif|jpg|png|jpeg";

    /**
     * User image dimensions
     *
     * @var integer[]
     */
    private static $userImageDimensions = [];

    
    /**
     * User banner dimensions
     *
     * @var integer[]
     */
    private static $userBannerDimensions = [];

    /**
     * The AWS Uploader object
     * @var \EasyShop\Upload\AwsUpload
     */
    private $awsUploader;
    
    /**
     * The environment of the application
     *
     * @var string 
     */
    private $environment;
    
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Config Loader
     *
     * @var EasyShop\ConfigLoader\ConfigLoader
     */
    private $configLoader;
    
    /**
     * Upload library
     *
     * @var CI_Upload
     */
    private $uploadLibrary;
    
    /**
     * Image Libarary
     *
     * @var Image_lib
     */
    private $imageLibrary;
    
    /**
     * Image Utility
     *
     * @var EasyShop\ImageImageUtility
     */
    private $imageUtility;
    

    
    /**
     * Inject dependencies
     * @param \EasyShop\Upload\AwsUpload $awsUploader
     */
    public function __construct($em, 
                                $awsUploader, 
                                $configLoader, 
                                $uploadLibrary, 
                                $imageLibrary, 
                                $environment = 'DEVELOPMENT',
                                $imageUtility)
    {
        $this->awsUploader = $awsUploader;
        $this->environment = $environment;
        $this->entityManager = $em;
        $this->configLoader = $configLoader;
        $this->uploadLibrary = $uploadLibrary;
        $this->imageLibrary = $imageLibrary;
        $this->imageUtility = $imageUtility;
        
        $userImageDimensions = $this->configLoader->getItem('image_dimensions')['userImagesSizes'];
        $this->userImageDimensions['normal'] = [
            'width' => $userImageDimensions['normalsize'][0],
            'height' =>  $userImageDimensions['normalsize'][1],
        ];
        $this->userImageDimensions['thumbnail'] = [
            'width' => $userImageDimensions['smallsize'][0],
            'height' =>  $userImageDimensions['smallsize'][1],
        ];
        $this->userBannerDimensions['width'] = $userImageDimensions['bannersize'][0];
        $this->userBannerDimensions['height'] = $userImageDimensions['bannersize'][1];

        $this->productImageDimensions = $this->configLoader->getItem('image_dimensions')['productImagesSizes']; 
    }
    

    /**
     * Uploads the temporary image directory structure into the appropriate location
     *
     * @param string $sourceDirectory
     * @param string $destinationDirectory
     * @param integer $productId
     * @param string[] $fileNames
     */
    public function uploadImageDirectory($sourceDirectory, $destinationDirectory, $productId, $fileNames = NULL)
    {    
        $sourceDirectory = rtrim($sourceDirectory,'/');
        $destinationDirectory = rtrim($destinationDirectory,'/');

        //creating the destination directory in the local server
        if(!is_dir($destinationDirectory)){
            mkdir($destinationDirectory, 0777, true);
        }
 
        $directoryMap = directory_map($sourceDirectory);
        foreach($directoryMap as $key => $file)
        {
            if(is_numeric($key)){
                if(in_array(strtolower($file),$fileNames)){
                    $explodedFilename = explode('_', $file);
                    $explodedFilename[0] = $productId;
                    $newFileName = implode('_', $explodedFilename);
                    if(strtolower($this->environment) !== 'development'){
                        $this->awsUploader->uploadFile(getcwd()."/".$sourceDirectory."/".$file, $destinationDirectory."/".$newFileName);
                    }
                    else{
                        /**
                         * Only move the image to the actual directory when in dev environement. Otherwise, the S3 images are used.
                         */
                        copy($sourceDirectory.'/'.$file,$destinationDirectory.'/'.$file);
                        rename($destinationDirectory.'/'.$file, $destinationDirectory.'/'.$newFileName);
                    }
                }
            }
            else{
                $this->uploadImageDirectory($sourceDirectory.'/'.$key, $destinationDirectory.'/'.$key,$productId,$fileNames);
            }
        }
    }
    
    
    /**
     * Uploads the user avatar. This method uploads the file in the $_FILES super global method
     *
     * @param integer $memberId
     * @param string $fieldName
     * @param string[] $cropData
     * @return mixed
     */
    public function uploadUserAvatar($memberId, $fieldName = "userfile", $cropData = [])
    {
        $fileSuperGlobal = $_FILES;
        $member = $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                      ->findOneBy(['idMember' => $memberId]);
        
        $result = [
            'error' => [],
            'member' => null
        ];
        $filenames = [
            'usersize', 
            '150x150', 
            '60x60'
        ];

        if($member){
            $imagePath = $member->getImgurl();
            $username = $member->getUsername();
            if(trim($imagePath) === "" || $imagePath === null){
                $imagePath = $this->configLoader->getItem('image_path', 'user_img_directory').$memberId.'_'.$username;
            }

            if(!is_dir($imagePath)){
                mkdir($imagePath,0755,true); 
            }
        
            $pathInfo = pathinfo($fileSuperGlobal[$fieldName]["name"]); // "userfile" is the form input field name
            $fileExtension = $pathInfo['extension']; 
            $filename = $filenames[0].'.'.$fileExtension;

            $config['overwrite'] = true;
            $config['file_name'] = $filename;
            $config['upload_path'] = $imagePath; 
            $config['allowed_types'] = self::ALLOWABLE_IMAGE_MIME_TYPES;
            $config['max_size'] = self::MAX_ALLOWABLE_SIZE_KB;
            $config['max_width']  = self::MAX_ALLOWABLE_DIMENSION_PX;
            $config['max_height']  = self::MAX_ALLOWABLE_DIMENSION_PX;
            $this->uploadLibrary->initialize($config); 
            
            if ( ! $this->uploadLibrary->do_upload($fieldName)){
                $result['error'] = $this->uploadLibrary->display_errors();
            }
            else{
                $config = [];
                $config['image_library'] = 'gd2';
                $config['source_image'] = $imagePath.'/'.$filename;
                $config['new_image'] = $imagePath.'/'.$filename;
                $config['maintain_ratio'] = false;
                $imageData = $this->uploadLibrary->data(); 
                $this->imageLibrary->initialize($config);
                $this->imageLibrary->convert('png', true);
                
                if($cropData['w'] > 0 && $cropData['h'] > 0){
                    $config['width'] = $cropData['w'];
                    $config['height'] = $cropData['h'];
                    $config['x_axis'] = $cropData['x'];
                    $config['y_axis'] = $cropData['y'];
                    $this->imageLibrary->initialize($config);  
                    $this->imageLibrary->image_process_gd('crop');
                    $this->imageLibrary->clear();
                    $config['x_axis'] = $config['y_axis'] = '';
                    
                    $imageData['image_width'] = $cropData['w'] - $cropData['x'];
                    $imageData['image_height'] = $cropData['h'] - $cropData['y'];
                }
                
                if( $imageData['image_width'] > self::MAX_IMAGE_HEIGHT || $imageData['image_height'] > self::MAX_IMAGE_WIDTH ){     
                    $config['width'] = self::MAX_IMAGE_HEIGHT;
                    $config['height'] = self::MAX_IMAGE_WIDTH;
                    $this->imageLibrary->initialize($config);  
                    $this->imageLibrary->resize(); 
                    $this->imageLibrary->clear();
                }
                $imageDimensions = $this->userImageDimensions;

                $config['new_image'] =  $imagePath.'/'.$filenames[1].'.png';
                $config['source_image'] = $imagePath.'/'.$filenames[0].'.png';
                $config['width'] = $imageDimensions['normal']['width'];
                $config['height'] = $imageDimensions['normal']['height'];
                $this->imageLibrary->initialize($config);  
                $this->imageLibrary->resize();   
                $this->imageLibrary->clear();
                
                $config['new_image'] =  $imagePath.'/'.$filenames[2].'.png';
                $config['source_image'] = $imagePath.'/'.$filenames[0].'.png';
                $config['width'] = $imageDimensions['thumbnail']['width'];
                $config['height'] = $imageDimensions['thumbnail']['height'];
                $this->imageLibrary->initialize($config);  
                $this->imageLibrary->resize();
                $this->imageLibrary->clear();
            }
      
            if(strtolower($this->environment) !== 'development'){
                try{
                    $this->awsUploader->uploadFile($imagePath.'/'.$filenames[0].'.png', $imagePath."/".$filenames[0].".png");
                    $this->awsUploader->uploadFile($imagePath.'/'.$filenames[1].'.png', $imagePath."/".$filenames[1].".png");
                    $this->awsUploader->uploadFile($imagePath.'/'.$filenames[2].'.png', $imagePath."/".$filenames[2].".png");
                    unlink($imagePath.'/'.$filenames[0].'.png');
                    unlink($imagePath.'/'.$filenames[1].'.png');
                    unlink($imagePath.'/'.$filenames[2].'.png');
                } catch(\Exception $e){
                    $result['error'][] = $e->getMessage();
                }
            }

            if(empty($result['error'])){
                $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                    ->updateMemberImageUrl($member, $imagePath);  
            }   
        }
        $result['member'] = $member;
        return $result;
    }
    
    
    
        
    /**
     * Uploads the user banner. This method uploads the file in the $_FILES super global method
     *
     * @param integer $memberId
     * @param string $fieldName
     * @param string[] $cropData
     * @return mixed
     */
    public function uploadUserBanner($memberId, $fieldName = "userfile", $cropData = array())
    {
        $fileSuperGlobal = $_FILES;
        $member = $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                      ->findOneBy(['idMember' => $memberId]);
        
        $result = [
            'error' => [], 
            'member' => null
        ];
        
        if($member){
            $imagePath = $member->getImgurl();
            $username = $member->getUsername();
            if(trim($imagePath) === "" || $imagePath === null){
                $imagePath = $this->configLoader->getItem('image_path', 'user_img_directory').$memberId.'_'.$username;
            }
            if(!is_dir($imagePath)){
                mkdir($imagePath,0755,true); 
            }
            
            $pathInfo = pathinfo($fileSuperGlobal[$fieldName]["name"]); // "userfile" is the form input field name
            $fileExtension = $pathInfo['extension']; 
            $filename = 'banner.'.$fileExtension;
            
            $config['overwrite'] = true;
            $config['file_name'] = $filename;
            $config['upload_path'] = $imagePath; 
            $config['allowed_types'] = self::ALLOWABLE_IMAGE_MIME_TYPES;
            $config['max_size'] = self::MAX_ALLOWABLE_SIZE_KB;
            $config['max_width']  = self::MAX_ALLOWABLE_DIMENSION_PX;
            $config['max_height']  = self::MAX_ALLOWABLE_DIMENSION_PX;
            $this->uploadLibrary->initialize($config); 
            if ( ! $this->uploadLibrary->do_upload($fieldName)){
                $result['error'] = $this->uploadLibrary->display_errors();
            }
            else{
                $config = [];
                $config['image_library'] = 'gd2';
                $config['source_image'] = $imagePath.'/'.$filename;
                $config['new_image'] = $imagePath.'/'.$filename;
                $config['maintain_ratio'] = false;
                $imageData = $this->uploadLibrary->data(); 
                $this->imageLibrary->initialize($config);
                $this->imageLibrary->convert('png', true);
                
                if($cropData['w'] > 0 && $cropData['h'] > 0){
                    $config['width'] = $cropData['w'];
                    $config['height'] = $cropData['h'];
                    $config['x_axis'] = $cropData['x'];
                    $config['y_axis'] = $cropData['y'];
                    $this->imageLibrary->initialize($config);  
                    $this->imageLibrary->image_process_gd('crop');
                    $config['x_axis'] = $config['y_axis'] = '';
                }

                $config['new_image'] = $imagePath.'/'.$filename;
                $config['width'] = $this->userBannerDimensions['width'];
                $config['height'] = $this->userBannerDimensions['height']; 
                $this->imageLibrary->initialize($config);
                $this->imageLibrary->resize(); 

                if(strtolower($this->environment) !== 'development'){
                    try{
                        $this->awsUploader->uploadFile($imagePath.'/banner.png', $imagePath.'/banner.png');
                        unlink($imagePath.'/banner.png');
                    } 
                    catch(\Exception $e){
                        $result['error'][] = $e->getMessage();
                    }
                }

                if(empty($result['error'])){
                    $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                        ->updateMemberImageUrl($member, $imagePath, false);  
                }   
            }
        }
        $result['member'] = $member;
        return $result;
    }

    /**
     * Uploads the product image. 
     * This method supports multiple uploads
     * This method uploads the file in the $_FILES super global method
     *
     * @param string $fileNames
     * @param string $pathDirectory
     * @param string $fieldName
     * @param mixed  $cropData
     * sample cropdata. based on $fileName count
     *        [
     *            0 => [
     *                    0 => x Axis,
     *                    1 => y Axis,
     *                    2 => width,
     *                    3 => height,
     *                 ],
     *        ]
     * @return mixed
     */
    public function uploadProductImage($fileNames, $pathDirectory, $fieldName = "userfile", $cropData = [])
    {
        $fileSuperGlobal = $_FILES;
        $isSuccess = false;
        $errorMessage = "";
        $finalFileNames = [];
        $dimensions = $this->productImageDimensions;

        if(file_exists($pathDirectory)) {
            $config = [
                "upload_path" => $pathDirectory,
                "overwrite" => false,
                "file_name" => $fileNames,
                "encrypt_name" => false,
                "remove_spaces" => true,
                "allowed_types" => self::ALLOWABLE_IMAGE_MIME_TYPES,
                "max_size" => self::MAX_ALLOWABLE_SIZE_KB,
                "xss_clean" => false,
                "max_width" => self::MAX_ALLOWABLE_DIMENSION_PX,
                "max_height" => self::MAX_ALLOWABLE_DIMENSION_PX,
            ];

            $this->uploadLibrary->initialize($config); 

            if($this->uploadLibrary->do_multi_upload($fieldName)){
                $fileData = $this->uploadLibrary->get_multi_upload_data();  
                for ($i=0; $i < sizeof($fileSuperGlobal[$fieldName]); $i++) {
                    if(isset($fileData[$i])){
                        $uploadData = $fileData[$i];
                        $file = $fileNames[$i];
                        $originalImage = $pathDirectory.$file;
                        $smallImage = $pathDirectory."small/".$file;
                        $categoryImage = $pathDirectory."categoryview/".$file;
                        $thumbnailImage = $pathDirectory."thumbnail/".$file;

                        if(empty($coordinate) === false && isset($cropData[$i])){
                            $coordinate = $cropData[$i];
                            $this->imageUtility->imageCrop($originalImage, 
                                                     $coordinate[0], 
                                                     $coordinate[1], 
                                                     $coordinate[2], 
                                                     $coordinate[3]);
                        }

                        if( $uploadData['image_width'] > self::MAX_IMAGE_HEIGHT 
                            || $uploadData['image_height'] > self::MAX_IMAGE_WIDTH ){ 
                            $this->imageUtility->imageResize($originalImage,
                                                             $originalImage,
                                                             $dimensions['max']);
                        }

                        $this->imageUtility->imageResize($originalImage, 
                                                   $smallImage,
                                                   $dimensions["small"]);

                        $this->imageUtility->imageResize($smallImage, 
                                                   $categoryImage,
                                                   $dimensions["categoryview"]);

                        $this->imageUtility->imageResize($categoryImage, 
                                                   $thumbnailImage,
                                                   $dimensions["thumbnail"]);

                        $finalFileNames[] = $file;
                    }
                }
                $isSuccess = true;
            }
            else{ 
                $errorMessage = $this->uploadLibrary->display_errors();
            } 
        }
        else{
            $errorMessage = "Path directory not exist!";
        }

        return [
            'isSuccess' => $isSuccess,
            'fileNames' => $finalFileNames,
            'errorMessage' => $errorMessage,
        ];
    }

    /**
     * Check if file type to be uploaded is valid
     * @param  file    $file available for using getimagesize function
     * @return boolean
     */
    public function checkValidFileType($file)
    {
        $mimeConfig = $this->configLoader->getItem('mimes');
        $arrayMimes = explode("|", self::ALLOWABLE_IMAGE_MIME_TYPES);
        $acceptableMime = [];
        foreach ($arrayMimes as $mime) {
            if(isset($mimeConfig[$mime])){
                if(is_array($mimeConfig[$mime])){
                    $acceptableMime = array_merge($acceptableMime, $mimeConfig[$mime]);
                }
                else{
                    $acceptableMime[] = $mimeConfig[$mime];
                }
            }
        }

        $fileData = getimagesize($file);
        if((bool)$fileData && in_array($fileData['mime'], $acceptableMime)){
            return true;
        }

        return false; 
    }

    /**
     * Check if file dimension to be uploaded is valid
     * @param  file    $file available for using getimagesize function
     * @return boolean
     */
    public function checkValidFileDimension($file)
    {
        list($width, $height) = $imageData = getimagesize($file); 
        if($imageData && $width <= self::MAX_ALLOWABLE_DIMENSION_PX && $height <= self::MAX_ALLOWABLE_DIMENSION_PX){
            return true;
        }
        return false;
    }
}

