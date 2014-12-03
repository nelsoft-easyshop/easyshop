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
}