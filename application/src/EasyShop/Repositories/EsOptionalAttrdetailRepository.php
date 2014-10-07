<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 
use EasyShop\Entities\EsOptionalAttrdetail;
use EasyShop\Entities\EsOptionalAttrhead;

class EsOptionalAttrdetailRepository extends EntityRepository
{

    /**
     * Method used for reverting rubbish data from admin product csv uploads
     * @param int $id
     */    
    public function deleteAttrDetailByProductId($id)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('pi')
                            ->from('EasyShop\Entities\EsOptionalAttrhead','pi')
                            ->where('pi.product = :productId')
                            ->setParameter('productId', $id)
                            ->getQuery();

        $queryResult = $qb->getResult();
        foreach ($queryResult as $result ) {

            $query = $this->em->createQuery("DELETE FROM EasyShop\Entities\EsOptionalAttrdetail e 
                WHERE e.head = ?2");
            $query->setParameter(2, $result->getIdOptionalAttrhead());
            $query->execute();
        }       
    }
}

