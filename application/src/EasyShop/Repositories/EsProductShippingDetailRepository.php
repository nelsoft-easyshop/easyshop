<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsProductShippingDetailRepository extends EntityRepository
{

    /**
     * Method used for reverting rubbish data from admin product csv uploads
     * @param int $id
     */
    public function deleteShippingDetailByProductItem($result)
    {
        $query = $this->_em->createQuery("DELETE FROM EasyShop\Entities\EsProductShippingDetail e 
        WHERE e.productItem = ?5");
        $query->setParameter(5, $result->getIdProductItem());
        $query->execute();
    }
}
