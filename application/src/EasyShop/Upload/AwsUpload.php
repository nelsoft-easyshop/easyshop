<?php

namespace Easyshop\Upload;

use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\Model\MultipartUpload\UploadBuilder;

/**
 * Amazon S3 Client Class Consumer
 *
 * @author Sam Gavinio <samgavinio@easyshop.ph>
 */
class AwsUpload
{
    /**
     * The amazon web service storage client
     * @var Aws/S3/S3Client
     */
    private $awsS3Client;
    
    /**
     * Assets config 
     *
     * @var string[]
     */
    private $assetsConfig;

    
    public function __construct($awsS3Client, $configLoader)
    {
        $this->awsS3Client = $awsS3Client;
        $this->assetsConfig = $configLoader->getItem('assets');
    }

    /**
     * Uploads a file to an AWS S3 bucket
     *
     * @param string $sourceFilePath
     * @param string $destinationFilePath
     * @return bool
     *
     */
    public function uploadFile($sourceFilePath, $destinationFilePath)
    {
        if(!file_exists($sourceFilePath)){
            return false;
        }
        
        $mimeType = image_type_to_mime_type(exif_imagetype($sourceFilePath));
        $fileExtension = explode('/', $mimeType)[1];
        $allowedFileTypes = explode('|', $this->assetsConfig['allowed_types']);
        $fileSizeByte = filesize($sourceFilePath) / 1024;
        
        if(!in_array($fileExtension, $allowedFileTypes) ||  $fileSizeByte > $this->assetsConfig['max_size'] ){
            return false;
        }
        
        $destinationFilePath = ltrim($destinationFilePath , '.');

        $result = $this->awsS3Client->putObject([
            'Bucket' => $this->assetsConfig['bucket'],
            'Key'    => $destinationFilePath,
            'SourceFile'  => $sourceFilePath,
            'ContentType' => $mimeType,
            'ACL'    => 'public-read',
            'CacheControl' => 'max-age=604800',
        ]);
            
        return $result;
    }
    
    /**
     * Checks if the file exists in the bucket
     *
     * @param string $sourceFileFullPath
     * @return boolean
     */
    public function doesFileExist($sourceFileFullPath)
    {
        $cleanSourceFileFullPathClean = strpos($sourceFileFullPath, '.') === 0 ? substr($sourceFileFullPath, 1) : $sourceFileFullPath;
        return $this->awsS3Client->doesObjectExist( $this->assetsConfig['bucket'], $cleanSourceFileFullPathClean);
    }

    
}


