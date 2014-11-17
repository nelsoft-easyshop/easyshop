<?php

namespace Easyshop\Upload;

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
     * The AWS S3 bucket
     * @var string
     */
    private $s3Bucket;

    public function __construct($awsS3Client, $configLoader)
    {
        $this->awsS3Client = $awsS3Client;
        $this->s3Bucket = $configLoader->getItem('assets')["bucket"];
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

        $fileExtension = image_type_to_mime_type(exif_imagetype($sourceFilePath));
        
        $result = $this->awsS3Client->putObject(array(
            'Bucket' => $this->s3Bucket,
            'Key'    => $destinationFilePath,
            'SourceFile'  => $sourceFilePath,
            'ContentType' => $fileExtension,
            'ACL'    => 'public-read',
        ));
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
        $doesExist = $this->awsS3Client->doesObjectExist( $this->s3Bucket, $cleanSourceFileFullPathClean);
        return $doesExist;
    }

    
}


