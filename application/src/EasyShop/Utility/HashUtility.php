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
        $getData = explode('~', $decrypted);

        return $getData;
    }
}
