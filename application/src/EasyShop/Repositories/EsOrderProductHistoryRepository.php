<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsOrderProductHistory;
use DateTime;

class EsOrderProductHistoryRepository extends EntityRepository
{
    /**
     * add history log
     * @param $esOrderProduct
     * @param $esOrderProductStatus
     * @param $historyLog
     * @return EsOrderProductHistory
     */
    public function createHistoryLog($esOrderProduct, $esOrderProductStatus, $historyLog)
    {
        $esOrderProductHistory = new EsOrderProductHistory();
        $esOrderProductHistory->setOrderProduct($esOrderProduct);
        $esOrderProductHistory->setOrderProductStatus($esOrderProductStatus);
        $esOrderProductHistory->setComment($historyLog);
        $esOrderProductHistory->setDateAdded(new DateTime('now'));
        $this->_em->persist($esOrderProductHistory);
        $this->_em->flush();

        return $esOrderProductHistory;
    }
}