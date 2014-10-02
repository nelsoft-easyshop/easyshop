<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

use EasyShop\Entities\EsProductImage;

class EsProductImageRepository extends EntityRepository
{
    
    /**
     * Returns the default image of a product
     *
     * @param integer $productId
     * @return EasyShop\Entities\EsProductImage
     *
     */
    public function getDefaultImage($productId)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('pi')
                            ->from('EasyShop\Entities\EsProductImage','pi')
                            ->where('pi.product = :productId')
                            ->andWhere('pi.isPrimary = 1')
                            ->setParameter('productId', $productId)
                            ->getQuery();

        $result = $qb->getOneOrNullResult();
        return $result;
    }

    /**
     * Returns the images of a product
     *
     * @param integer $productId
     * @return EasyShop\Entities\EsProductImage
     *
     */
    public function getProductImages($productId)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('pi')
                            ->from('EasyShop\Entities\EsProductImage','pi')
                            ->where('pi.product = :productId')
                            ->setParameter('productId', $productId)
                            ->getQuery();

        $result = $qb->getResult();
        return $result;
    }

    /**
     * Method used for reverting rubbish data from admin product csv uploads
     * @param int $id
     */
    public function deleteImageByProductId($id)
    {
        $query = $this->_em->createQuery("DELETE FROM EasyShop\Entities\EsProductImage e 
        WHERE e.product = ?1");
        $query->setParameter(1, $id);
        $query->execute();

    }
    
}
