<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;

class EsPaymentBankdepositRepository extends EntityRepository
{
    /**
     * Get payment bank deposit
     *
     * @param $orderId
     * @return bool|null|object
     */
    public function getTransactionBankDepositDetailsByOrderId($orderId)
    {
        $this->em = $this->_em;
        $transactionBankDepositDetails = false;
        $orderDetails = $this->em->getRepository('EasyShop\Entities\EsOrder')->find($orderId);

        if ($orderDetails) {
            $transactionBankDepositDetails =
                $this->em->getRepository('EasyShop\Entities\EsPaymentBankdeposit')
                         ->findOneBy(['order' => $orderDetails]);
        }

        return $transactionBankDepositDetails;
    }
}
