<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsVerifcode; 

class EsVerifcodeRepository extends EntityRepository
{
    /**
     * Stores member's verification code
     *
     * @param EasyShop\Entities\EsMember $member
     * @param string $emailCode
     * @param string $mobileCode
     * @return bool
     */
    public function createNewMemberVerifCode($member, $emailCode, $mobileCode)
    {

        try{
            $verifCode = new EsVerifcode();     
            $verifCode->setMember($member);
            $verifCode->setEmailcode($emailCode);
            $verifCode->setMobilecode($mobileCode);
            $verifCode->setDate(new \DateTime('now'));
            $verifCode->setFpTimestamp(new \DateTime('now'));
            $verifCode->setEmailcount(1);
            $verifCode->setMobilecount(\EasyShop\Entities\EsVerifcode::DEFAULT_MOBILE_COUNT);
            $this->_em->persist($verifCode);
            $this->_em->flush();
            return true;
        }
        catch(\Doctrine\ORM\Query\QueryException $e) {
            return false;
        }
    }    
    
    /**
     * Update the verif code
     *
     * @param EasyShop\Entities\EsVerifcode $verifCode
     * @param string $emailSecretHash
     * @param string $mobileCode
     * @return bool
     */
    public function updateVerifCode($verifCode, $emailSecretHash, $mobileCode)
    {
        $verifCode->setEmailcode($emailSecretHash);
        $verifCode->setMobilecode($mobileCode);
        $verifCode->setEmailcount(0);
        $verifCode->setDate(new \DateTime('now'));
        $verifCode->setFpTimestamp(new \DateTime('now'));
        $isSuccessful = true;
        try{
            $this->_em->flush();
        }
        catch(\Exception $e){
            $isSuccessful = false;
        }
        return $isSuccessful;
    }
    
}

