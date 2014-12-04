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
     * @return EasyShop\Entities\EsBillingInfo
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
        if($defaultAccount !== null){
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
        return $isSuccessful  ? $paymentAccount : false;
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
        $accounts =  $em->getRepository('EasyShop\Entities\EsBillingInfo')
                        ->findBy(['member' => $memberId,
                                    'isDefault' => true,
                                    'isDelete' => false,
                            ]);
        return !empty($accounts) ? $accounts[0] : null;
    }

    /**
     * Updates the default payment account
     *
     * @param integer $memberId
     * @param integer $paymentAccountId
     */
    public function updateDefaultAccount($memberId, $paymentAccountId)
    {
        $em = $this->_em;
        $defaultAccounts = $em->getRepository('EasyShop\Entities\EsBillingInfo')
                              ->findBy(['member' => $memberId, 
                                        'isDefault' => true,
                                        'isDelete' => false,
                              ]);
        $dateToday = date_create(date("Y-m-d H:i:s"));
        foreach($defaultAccounts as $defaultAccount){
            $defaultAccount->setIsDefault(false);
            $defaultAccount->setDatemodified($dateToday);
        }
        $newDefaultAccount =  $em->getRepository('EasyShop\Entities\EsBillingInfo')
                                 ->findBy(['member' => $memberId, 
                                           'idBillingInfo' => $paymentAccountId,
                                           'isDelete' => false,
                                         ]);
       if(!empty($newDefaultAccount)){
            $newDefaultAccount[0]->setIsDefault(true);
            $newDefaultAccount[0]->setDatemodified($dateToday);
            $em->flush();
        }                                    
    }
    
    /**
     * Deletes a payment account. Sets a new default account if the current
     * account is the default account
     *
     * @param integer $memberId
     * @param integer $paymentAccountId
     * @return bool
     */
    public function deletePaymentAccount($memberId, $paymentAccountId)
    {
        $em = $this->_em;
        $paymentAccounts = $em->getRepository('EasyShop\Entities\EsBillingInfo')
                             ->findBy(['member' => $memberId, 
                                       'idBillingInfo' => $paymentAccountId,
                                       'isDelete' => false,
                             ]);
        $isSuccessful = false;
        if(!empty($paymentAccounts)){
            $accountForDeletion = $paymentAccounts[0];
            $isDefault = $accountForDeletion->getIsDefault();
            $accountForDeletion->setIsDelete(true);
            $accountForDeletion->setIsDefault(false);
            $accountForDeletion->setDatemodified(date_create(date("Y-m-d H:i:s")));
            $em->flush();
            $isSuccessful = true;
            if($isDefault){
                $availableAccounts  = $em->getRepository('EasyShop\Entities\EsBillingInfo')
                                         ->findBy(['member' => $memberId, 
                                                   'isDelete' => false,
                                         ]);
                if(!empty($availableAccounts)){
                    $this->updateDefaultAccount($memberId, $availableAccounts[0]->getIdBillingInfo());
                }
           }  
        }
        return $isSuccessful;
    }

    
}


