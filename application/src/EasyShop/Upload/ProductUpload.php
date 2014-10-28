<?php

namespace Easyshop\Upload;

/**
 * Product Upload Class
 *
 * @author Sam Gavinio <samgavinio@easyshop.ph>
 */
class ProductUpload
{

    /**
     * The AWS Uploader object
     * @var \EasyShop\Upload\AwsUpload
     */
    private $awsUploader;
    
    
    /**
     * Inject dependencies
     * @param \EasyShop\Upload\AwsUpload $awsUploader
     */
    public function __construct($awsUploader)
    {
        $this->awsUploader = $awsUploader;
    }
    
     
    /**
     * Uploads the temporary image directory structure into the S3 bucket 
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
        $sourceDirectory = ltrim($sourceDirectory,'.');
        $destinationDirectory = ltrim($destinationDirectory,'.');
        $directoryMap = directory_map($sourceDirectory);

        foreach($directoryMap as $key => $file)
        {
            if(is_numeric($key)){
                if(in_array(strtolower($file),$fileNames)){
                    $explodedFilename = explode('_', $file);
                    $explodedFilename[0] = $productId;
                    $newFileName = implode('_', $explodedFilename);
                    $this->awsUploader->uploadFile(getcwd()."/".$sourceDirectory."/".$file, $destinationDirectory."/".$newFileName);
                }
            }
            else{
                $this->uploadImageDirectory($sourceDirectory.'/'.$key, $destinationDirectory.'/'.$key,$productId,$fileNames);
            }
        }
    }
    
}