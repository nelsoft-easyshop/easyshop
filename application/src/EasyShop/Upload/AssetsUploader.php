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
    
    const USER_BANNER_HEIGHT = 366;
    
    const USER_BANNER_WIDTH = 1475;
    
    const ALLOWABLE_IMAGE_MIME_TYPES = "gif|jpg|png|jpeg";

    /**
     * User image dimensions
     * Defined as a class variable as PHP does not allow immutable arrays.
     * Acess via the appropriate getter method.
     *
     * @var integer[]
     */
    private static $userImageDimensions = [['width' => 157, 'height' => 150], ['width' => 60, 'height' => 60 ]];


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
     * Inject dependencies
     * @param \EasyShop\Upload\AwsUpload $awsUploader
     */
    public function __construct($em, $awsUploader, $configLoader, $uploadLibrary, $imageLibrary, $environment = 'DEVELOPMENT')
    {
        $this->awsUploader = $awsUploader;
        $this->environment = $environment;
        $this->entityManager = $em;
        $this->configLoader = $configLoader;
        $this->uploadLibrary = $uploadLibrary;
        $this->imageLibrary = $imageLibrary;
    }
    
    /**
     * Returns the user image dimensions
     *
     * @return integer[]
     */
    public function getUserImageDimensions()
    {
        return self::$userImageDimensions;
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
        
        if(strtolower($this->environment) !== 'development'){
            $sourceDirectory = ltrim($sourceDirectory,'.');
            $destinationDirectory = ltrim($destinationDirectory,'.');
        }
        else{
            //creating the destination directory
            if(!is_dir($destinationDirectory)){
                mkdir($destinationDirectory, 0777, true);
            }
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
                        copy($sourceDirectory.'/'.$file,$destinationDirectory.'/'.$file);
                        rename($destinationDirectory.'/'.$object_value, $destinationDirectory.'/'.$newFileName);
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
    public function uploadUserAvatar($memberId, $fieldName = "userfile", $cropData = array())
    {
        $fileSuperGlobal = $_FILES;
        $member = $this->entityManager->getRepository('EasyShop\Entities\EsMember')->findOneBy(['idMember' => $memberId]);
        
        $result = ['error' => array(), 'member' => null];
        $filenames = ['usersize', '150x150', '60x60'];

        if($member){
            $imagePath = $member->getImgurl();
            $username = $member->getUsername();
            if(trim($imagePath) === "" || $imagePath === null){
                $imagePath = $this->configLoader->getItem('image_path', 'config_loader').$memberId.'_'.$username;
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
                $config = array();
                $config['image_library'] = 'gd2';
                $config['source_image'] = $imagePath.'/'.$filename;
                $config['new_image'] = $imagePath.'/'.$filename;
                $config['maintain_ratio'] = true;
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
                $imageDimensions = self::$userImageDimensions;
                $config['new_image'] =  $imagePath.'/'.$filenames[1].'.png';
                $config['source_image'] = $imagePath.'/'.$filenames[0].'.png';
                $config['width'] = $imageDimensions[0]['width'];
                $config['height'] = $imageDimensions[0]['height'];
                $this->imageLibrary->initialize($config);  
                $this->imageLibrary->resize();   
                $this->imageLibrary->clear();
                
                $config['new_image'] =  $imagePath.'/'.$filenames[2].'.png';
                $config['source_image'] = $imagePath.'/'.$filenames[0].'.png';
                $config['width'] = $imageDimensions[1]['width'];
                $config['height'] = $imageDimensions[1]['height'];
                $this->imageLibrary->initialize($config);  
                $this->imageLibrary->resize();
                $this->imageLibrary->clear();
            }
      
            if(strtolower($this->environment) !== 'development' || true){
                try{
                    $this->awsUploader->uploadFile($imagePath.'/'.$filenames[0].'.png', $imagePath."/".$filenames[0].".png");
                    $this->awsUploader->uploadFile($imagePath.'/'.$filenames[1].'.png', $imagePath."/".$filenames[1].".png");
                    $this->awsUploader->uploadFile($imagePath.'/'.$filenames[2].'.png', $imagePath."/".$filenames[2].".png");
                } catch(\Exception $e){
                    $result['error'][] = $e->getMessage();
                }
            }
            
            if(empty($result['error'])){
                $member->setImgurl($imagePath);
                $member->setIsHideAvatar(false);
                $member->setLastmodifieddate(new DateTime('now'));
                $this->entityManager->flush();
            }   
        }
        $result['member'] = $member;
        return $result;
    }
    
    
    public function uploadUserBanner($memberId, $fieldName = "userfile", $cropData = array())
    {
        $fileSuperGlobal = $_FILES;
        $member = $this->entityManager->getRepository('EasyShop\Entities\EsMember')->findOneBy(['idMember' => $memberId]);
        
        $result = ['error' => array(), 'member' => null];
        if($member){
            $imagePath = $member->getImgurl();
            $username = $member->getUsername();
            if(trim($imagePath) === "" || $imagePath === null){
                $imagePath = $this->configLoader->getItem('image_path', 'config_loader').$memberId.'_'.$username;
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
                $config = array();
                $config['image_library'] = 'gd2';
                $config['source_image'] = $imagePath.'/'.$filename;
                $config['new_image'] = $imagePath.'/'.$filename;
                $config['maintain_ratio'] = true;
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
                }

                $config['new_image'] = $imagePath.'/'.$filename;
                $config['width'] = self::USER_BANNER_WIDTH;
                $config['height'] = self::USER_BANNER_HEIGHT;
                $this->imageLibrary->initialize($config);
                $this->imageLibrary->resize(); 
                $this->imageLibrary->clear();
                
                
                if(strtolower($this->environment) !== 'development' || true){
                    try{
                        $this->awsUploader->uploadFile($imagePath.'/banner.png', $imagePath.'/banner.png');
                    } catch(\Exception $e){
                        $result['error'][] = $e->getMessage();
                    }
                }

                if(empty($result['error'])){
                    $member->setImgurl($imagePath);
                    $member->setIsHideAvatar(false);
                    $member->setLastmodifieddate(new DateTime('now'));
                    $this->entityManager->flush();
                }   
            }
        }
        $result['member'] = $member;
        return $result;
    }
    
}

