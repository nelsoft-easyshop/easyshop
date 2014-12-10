<?php
namespace EasyShop\Utility;

class HashUtility
{
    private $encrypt;

    /**
     * @param $encrypt
     */
    public function __construct($encrypt)
    {
        $this->encrypt = $encrypt;
    }

    /**
     * Decode hashed string
     * @param $data
     * @return array
     */
    public function decode($data)
    {
        $hash = html_escape($data);
        $enc = str_replace(" ", "+", $hash);
        $decrypted = $this->encrypt->decode($enc);
        $getData = unserialize($decrypted);

        return $getData;
    }

    /**
     * Returns random Alpha-numeric strings based on the passed parameter string length
     * @param int $length
     * @return string
     */
    public function generateRandomAlphaNumeric($length)
    {
        $characters = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789';
        $string = '';
        for ($i = 0; $i < $length; $i++) 
        {
              $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $string;
    }        
}
