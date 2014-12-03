<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsBillingInfo;

class EsBillingInfoRepository extends EntityRepository
{
    /**
     * Gets all the payment accounts for a given member 
     *
     * @param integer $memberId
     * @return mixed
     */
    public function getMemberPaymentAccountsAsArray($memberId)
    {
        $em = $this->_em;       
        $paymentAccounts = $em->createQueryBuilder()
                              ->select(
                                    'b.idBillingInfo',
                                    'bank.bankName', 
                                    'bank.bankShortName',
                                    'b.userAccount',
                                    'bank.idBank',
                                    'b.bankAccountName',
                                    'b.bankAccountNumber',
                                    'b.isDefault',
                                    'b.datemodified'
                              )
                              ->from('EasyShop\Entities\EsBillingInfo','b')
                              ->leftJoin('EasyShop\Entities\EsBankInfo', 'bank', 'WITH', 'bank.idBank = b.bankId')
                              ->where('b.member = :memberId')
                              ->andWhere('b.isDelete = 0')
                              ->orderBy('b.isDefault', 'DESC')
                              ->setParameter('memberId', $memberId)
                              ->getQuery()
                              ->getResult();

       return $paymentAccounts;
    }

    /**
     * Creates a new payment account
     *
     * @param string $accountName
     * @param string $accountNumber
     * @param integer $bankId
     */
    public function createNewPaymentAccount($memberId, $accountName, $accountNumber, $bankId)
    {
        $em = $this->_em;    
        $member = $em->find('EasyShop\Entities\EsMember', ['idMember' => $memberId]);
        $dateToday = date_create(date("Y-m-d H:i:s"));
        $paymentAccount = new EsBillingInfo();
        $paymentAccount->setBankAccountName($accountName);
        $paymentAccount->setBankAccountNumber($accountNumber);
        $paymentAccount->setBankId($bankId);
        $paymentAccount->setDateadded($dateToday);
        $paymentAccount->setDatemodified($dateToday);
        $paymentAccount->setMember($member);
        $isDefault = 1;
        $defaultAccount = $this->getDefaultAccount($memberId);
        if($defaultAccount){
            $isDefault = 0;
        }
        $paymentAccount->setIsDefault($isDefault);
        $isSuccessful = true;
        try{
            $em->persist($paymentAccount);
            $em->flush();
        }
        catch(Exception $e){
            $isSuccessful = false;
        }
        return $isSuccessful;
    }
    
    /**
     * Gets the default account
     * 
     * @param integer $memberId
     * @return EasyShop\Entities\EsBillingInfo
     */
    public function getDefaultAccount($memberId)
    {
        $em = $this->_em; 
        return $em->getRepository('EasyShop\Entities\EsBillingInfo')
                  ->findBy(['member' => $memberId,
                            'isDefault' => true,
                         ]);
    }
}

