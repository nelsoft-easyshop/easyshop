<?php

namespace Easyshop\Upload;

/**
 * Easyshop Upload Class
 *
 * @author Sam Gavinio <samgavinio@easyshop.ph>
 */
class AssetsUploader
{

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
     * Image Utility
     *
     * @var EasyShop\Image\ImageUtility
     */
    private $imageUtility;
    
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
    public function __construct($em, $awsUploader, $imageUtility, $configLoader, $uploadLibrary, $imageLibrary, $environment = 'DEVELOPMENT')
    {
        $this->awsUploader = $awsUploader;
        $this->environment = $environment;
        $this->entityManager = $em;
        $this->imageUtility = $imageUtility;
        $this->configLoader = $configLoader;
        $this->uploadLibrary = $uploadLibrary;
        $this->imageLibrary = $imageLibrary;
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
     * 
     *
     */
    public function uploadUserAvatar($memberId, $cropData = array())
    {
        $fileSuperGlobal = $_FILES;
        $member = $this->entityManager->getRepository('EasyShop\Entities\EsMember')->findOneBy(['idMember' => $memberId]);
        $result = ['error' => array(), 'imagePath' => null];
        $filenames = ['usersize', '150x150', '60x60'];
        if($member){
            $imagePath = $member->getImgurl();
            $username = $member->getUsername();
            if(trim($imagePath) === "" || $imagePath === null){
                $imagePath = $this->configLoader->getItem('image_path', 'config_loader').$memberId.'_'.$username;
            }
            
            if(strtolower($this->environment) === 'development'){
                if(!is_dir($imagePath)){
                    mkdir($imagePath,0755,true); 
                }
           
                $pathInfo = pathinfo($fileSuperGlobal["userfile"]["name"]); // "userfile" is the form input field name
                $fileExtension = $pathInfo['extension']; 
                $filename = $filenames[0].'.'.$fileExtension;

                $config['overwrite'] = true;
                $config['file_name'] = $filename;
                $config['upload_path'] = $imagePath; 
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '5000';
                $config['max_width']  = '5000';
                $config['max_height']  = '5000';
                $this->uploadLibrary->initialize($config); 
                if ( ! $this->uploadLibrary->do_upload()){
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
                    
                    // If cropped
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
                    
                    if( $imageData['image_width'] > 1024 || $imageData['image_height'] > 768 ){     
                        $config['width'] = 1024;
                        $config['height'] = 768;
                        $this->imageLibrary->initialize($config);  
                        $this->imageLibrary->resize(); 
                          $this->imageLibrary->clear();
                    }
                    
                    $config['new_image'] =  $imagePath.'/'.$filenames[1].'.png';
                    $config['source_image'] = $imagePath.'/'.$filenames[0].'.png';
                    $config['width'] = 157;
                    $config['height'] = 150;
                    $this->imageLibrary->initialize($config);  
                    $this->imageLibrary->resize();   
                    $this->imageLibrary->clear();
                    
                    $config['new_image'] =  $imagePath.'/'.$filenames[2].'.png';
                    $config['source_image'] = $imagePath.'/'.$filenames[0].'.png';
                    $config['width'] = 60;
                    $config['height'] = 60;
                    $this->imageLibrary->initialize($config);  
                    $this->imageLibrary->resize();
                    $this->imageLibrary->clear();
                    
                }
            }
            else{
                $this->awsUploader->uploadFile($fileSuperGlobal["userfile"]["tmp_name"], $imagePath."/".$filename);
            }
            print_r($result);
            
            
        }
    }
    
}