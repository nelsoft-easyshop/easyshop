<?php
namespace EasyShop\Utility;

use Doctrine\ORM\Query\ResultSetMapping;

class HashUtility
{
    private $encrypt;
    
    /**
     * Doctrine Entity manager
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param $encrypt
     */
    public function __construct($encrypt, $em)
    {
        $this->encrypt = $encrypt;
        $this->em = $em;
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
    
    /**
     * Generate a hash from two strings, previously implemented in a stored procedure
     *
     * @param string $string1
     * @param string $string2
     * @return string
     */
    public function generalPurposeHash($string1, $string2)
    {
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('hash', 'hash');
        $sql = "SELECT reverse(PASSWORD(concat(md5(:string1),sha1(:string2)))) as hash";
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('string1', $string1);
        $query->setParameter('string2', $string2); 
        $result = $query->getOneOrNullResult();

        return $result['hash'];
    }    
    
}
