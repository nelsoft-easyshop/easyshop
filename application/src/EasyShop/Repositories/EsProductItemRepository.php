<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsProductItemRepository extends EntityRepository
{

    /**
     * Method used for reverting rubbish data from admin product csv uploads
     * @param int $id
     */
    public function deleteProductItemByProductID($id)
    {
        $query = $this->_em->createQuery("DELETE FROM EasyShop\Entities\EsProductItem e 
            WHERE e.product = ?7");
        $query->setParameter(7, $id);
        $query->execute(); 
    }

    public function getProductItemByProductId($id)
    {
        $this->em = $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('pi')
                            ->from('EasyShop\Entities\EsProductItem','pi')
                            ->where('pi.product = :productId')
                            ->setParameter('productId', $id)
                            ->getQuery();
        return $qb->getOneOrNullResult();
    }
}
