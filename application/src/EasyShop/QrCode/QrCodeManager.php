<?php

namespace EasyShop\QrCode;

class QrCodeManager
{
    const FILE_EXTENSION_PNG = '.png';

    const IMAGE_PATH = 'assets/qrCode/';

    /**
     * QR code size for printing
     */
    const IMAGE_SIZE_PRINT = 10;

    /**
     * QR Code Library
     * @var mixed
     */
    private $qrCodeLibrary;

    /**
     * @param $qrCodeLibrary
     */
    public function __construct($qrCodeLibrary)
    {
        $this->qrCodeLibrary = $qrCodeLibrary;
    }

    /**
     * Convert text to QR Code and Save Image
     *
     * @param $text
     * @param $fileName
     * @param $level
     * @param $size
     * @param $padding
     * @param string $imagePath
     * @param string $extension
     */
    public function save($text, $fileName, $level, $size, $padding, $imagePath = self::IMAGE_PATH, $extension = self::FILE_EXTENSION_PNG)
    {
        $this->qrCodeLibrary->png($text,
            $imagePath.$fileName.$extension,
            $level,
            $size,
            $padding);
    }

    /**
     * Returns the image URL
     *
     * @param $filename
     * @param string $extension
     * @return string
     */
    public function getImagePath($filename, $extension = self::FILE_EXTENSION_PNG)
    {
        return $this->getImageDirectory() . $filename . $extension;
    }

    /**
     * Returns the image directory constant
     *
     * @return string
     */
    public function getImageDirectory()
    {
        return self::IMAGE_PATH;
    }

    /**
     * Returns the image size constant
     *
     * @return string
     */
    public function getImageSizeForPrinting()
    {
        return self::IMAGE_SIZE_PRINT;
    }

}
