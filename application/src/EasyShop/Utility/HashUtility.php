<?php
namespace EasyShop\Utility;

class HashUtility
{
    private $encrypt;

    /**
     * @param $encrypt
     */
    public function __contruct($encrypt)
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
        $getdata = explode('~', $decrypted);

        return $getdata;
    }
}
